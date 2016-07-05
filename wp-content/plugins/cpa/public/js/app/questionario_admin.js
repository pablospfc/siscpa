var questionario = angular.module('QuestionarioApp', ['SiscpaApp', 'ngRoute', 'angularModalService', 'ng.deviceDetector']);

questionario.config(function ($routeProvider, PATH) {
        $routeProvider
            .when('/lista', {
                templateUrl: PATH.postQuery({page: 'cpa_questionario', action: 'listView'}),
                controller: 'QuestionarioListaController'
            })
            .when('/previsualizar/:questionario_id', {
                templateUrl: PATH.postQuery({page: 'cpa_questionario', action: 'previewView'}),
                controller: 'QuestionarioPrevisualizarController'
            })
            .when('/criar', {
                templateUrl: PATH.postQuery({page: 'cpa_questionario', action: 'createView'}),
                controller: 'QuestionarioCriarController'
            })
            .when('/atualizar', {
                templateUrl: PATH.postQuery({page: 'cpa_questionario', action: 'updateView'}),
                controller: 'QuestionarioAtualizarController'
            })
            //rota principal
            .otherwise({redirectTo: '/lista'});
    }
);

//Responsável pelo menu de navegação
questionario.controller("navMenuController", function ($scope, $route) {

    $scope.isTabActive = function (tabName) {
        if (angular.isUndefined($route.current))
            return;
        return tabName == $route.current.controller ? "active" : null;
    };

});

//Service de Questionario
questionario.service('QuestionarioService', function ($request, PATH) {

    this.getList = function ($scope) {
        $scope.alert.changeShow(false);
        $request.get(PATH.AJAX)
            .addParams({
                page: 'cpa_questionario',
                action: 'getList'
            })
            .load($scope.loading.getRequestLoad('Carregando a lista de Questionários...'))
            .send(function (data) {
                $scope.questionarios = data;
            }, function (meta) {
                $scope.questionarios = [];
                $scope.alert.responseError(meta);
                $scope.alert.changeType('danger');
            });
    };

    this.getInfo = function ($scope) {
        $scope.alert.changeShow(false);
        $request.get(PATH.AJAX)
            .addParams({
                page: 'cpa_questionario',
                action: 'getInfo'
            })
            .load($scope.loading.getRequestLoad('Carregando informações do formulário de questionário...'))
            .send(function (data) {
                $scope.info = data;
            }, function (meta) {
                $scope.info = undefined;
                $scope.alert.responseError(meta);
                $scope.alert.changeType('danger');
            });
    };

    this.getQuestionario = function ($scope) {
        $scope.alert.changeShow(false);
        $request.get(PATH.AJAX)
            .addParams({
                page: 'cpa_questionario',
                action: 'read',
                id: $scope.questionarioId
            })
            .load($scope.loading.getRequestLoad('Carregando o questionário...'))
            .send(function (data) {
                $scope.questionario = data;
            }, function (meta) {
                $scope.questionario = undefined;
                $scope.alert.responseError(meta);
                $scope.alert.changeType('danger');
            });
    };

    this.postQuestionario = function ($scope) {
        var QuestionarioService = this;
        $scope.alert.changeShow(false);
        $request.post(PATH.AJAX)
            .addParams({
                page: 'cpa_questionario',
                action: 'create'
            })
            .addData($scope.formulario)
            .load($scope.loading.getRequestLoad('Salvando o questionário...'))
            .send(function (data) {
                $scope.formulario = QuestionarioService.getForm();
                $scope.alert.responseSuccess(data.message)
            }, function (meta) {
                $scope.alert.responseError(meta);
                $scope.alert.changeType('danger');
            });
    };

    this.deleteQuestionario = function ($scope) {
        $scope.alert.changeShow(false);
        $request.delete(PATH.AJAX)
            .addParams({
                page: 'cpa_questionario',
                action: 'delete'
            })
            .addData({
                id: $scope.questionario.id
            })
            .load($scope.loading.getRequestLoad('Apagando o questionário...'))
            .send(function (data) {
                var index = $scope.questionarios.indexOf($scope.questionario);

                if(index > -1)
                    $scope.questionarios.splice(index, 1);

                $scope.questionario = undefined;
                $scope.alert.responseSuccess(data.message);
                $scope.alert.changeTitle("");
            }, function (meta) {
                $scope.questionario = undefined;
                $scope.alert.responseError(meta);
                $scope.alert.changeType('danger');
            });
    };

    this.getForm = function () {
        return {
            nome: "",
            data_inicio: "",
            data_fim: "",
            id_tipo_usuario: undefined,
            tipo_usuario: {},
            topicos: [{
                nome: "Sem Tópico",
                existe: false,
                ordem: "0",
                perguntas: []
            }]
        };
    };

    this.getTopico = function () {
        return {
            nome: "",
            ordem: "",
            existe: true,
            perguntas: []
        };
    };

    this.getPergunta = function (topico, dimensao, ordem) {
        return {
            nome: "",
            ordem: !isNaN(ordem) ? eval(ordem + "+1") : ordem,
            id_dimensao: angular.isDefined(dimensao) ? dimensao.id : dimensao,
            dimensao: dimensao,
            topico: topico
        };
    }

});

questionario.controller('QuestionarioListaController', function ($scope, QuestionarioService) {
    $scope.questionarios = [];
    QuestionarioService.getList($scope);

    $scope.deleteQuestionario = function (questionario) {
        if(confirm("Você realmente gostaria de excluir o formulário '" + questionario.nome +"' ?")){
            $scope.questionario = questionario;
            QuestionarioService.deleteQuestionario($scope);
        }
    };

});

questionario.controller('QuestionarioPrevisualizarController', function ($scope, QuestionarioService, $routeParams) {
    $scope.questionario = undefined;
    $scope.questionarioId = $routeParams.questionario_id;
    QuestionarioService.getQuestionario($scope);
});

questionario.controller('QuestionarioCriarController', function ($scope, QuestionarioService, $location, deviceDetector, PATH, ModalService) {
    var device        = deviceDetector;
    $scope.formulario = QuestionarioService.getForm();
    $scope.pergunta   = QuestionarioService.getPergunta($scope.formulario.topicos[0]);
    QuestionarioService.getInfo($scope);

    $scope.isFirefox = function () {
        return device.raw.browser.firefox
    };

    $scope.mascaraData = function (campo) {
        var pass = $scope.formulario[campo];
        var expr = /[0123456789]/;

        for (i = 0; i < pass.length; i++) {
            // charAt -> retorna o caractere posicionado no índice especificado
            var lchar = pass.charAt(i);
            var nchar = pass.charAt(i + 1);

            if (i == 0) {
                // search -> retorna um valor inteiro, indicando a posição do inicio da primeira
                // ocorrência de expReg dentro de instStr. Se nenhuma ocorrencia for encontrada o método retornara -1
                // instStr.search(expReg);
                if ((lchar.search(expr) != 0) || (lchar > 3))
                    $scope.formulario[campo] = "";

            } else if (i == 1) {

                if (lchar.search(expr) != 0) {
                    // substring(indice1,indice2)
                    // indice1, indice2 -> será usado para delimitar a string
                    var tst1 = pass.substring(0, (i));
                    $scope.formulario[campo] = tst1;
                    continue;
                }

                if ((nchar != '/') && (nchar != '')) {
                    var tst1 = pass.substring(0, (i) + 1);

                    if (nchar.search(expr) != 0)
                        var tst2 = pass.substring(i + 2, pass.length);
                    else
                        var tst2 = pass.substring(i + 1, pass.length);

                    $scope.formulario[campo] = tst1 + '/' + tst2;
                }

            } else if (i == 4) {

                if (lchar.search(expr) != 0) {
                    var tst1 = pass.substring(0, (i));
                    $scope.formulario[campo] = tst1;
                    continue;
                }

                if ((nchar != '/') && (nchar != '')) {
                    var tst1 = pass.substring(0, (i) + 1);

                    if (nchar.search(expr) != 0)
                        var tst2 = pass.substring(i + 2, pass.length);
                    else
                        var tst2 = pass.substring(i + 1, pass.length);

                    $scope.formulario[campo] = tst1 + '/' + tst2;
                }
            }

            if (i >= 6) {
                if (lchar.search(expr) != 0) {
                    var tst1 = pass.substring(0, (i));
                    $scope.formulario[campo] = tst1;
                }
            }
        }

        if (pass.length > 10)
            $scope.formulario[campo] = pass.substring(0, 10);
        return true;
    };

    $scope.selecionarTipoUsuario = function (tipoUsuario) {
        $scope.formulario.id_tipo_usuario = tipoUsuario.id;
        $scope.formulario.tipo_usuario    = tipoUsuario;
    };

    $scope.selecionarDimensao = function (dimensao) {
        $scope.pergunta.id_dimensao = dimensao.id;
        $scope.pergunta.dimensao    = dimensao;
    };

    $scope.selecionarTopico = function (topico) {
        $scope.pergunta.topico = topico;
    };
    
    $scope.verificarAdicionarPergunta = function () {
        return !(
            angular.isUndefined($scope.pergunta.topico) ||
            angular.isUndefined($scope.pergunta.dimensao) ||
            $scope.pergunta.nome == "" ||
            $scope.pergunta.ordem == "" ||
            isNaN($scope.pergunta.ordem)
        );
    };

    $scope.adicionarPergunta = function () {
        if(!$scope.verificarAdicionarPergunta())
            return;
        
        var pergunta = angular.copy($scope.pergunta);
        delete pergunta.topico;
        $scope.pergunta.topico.perguntas.push(pergunta);
        $scope.pergunta = QuestionarioService.getPergunta(
            $scope.pergunta.topico,
            $scope.pergunta.dimensao,
            $scope.pergunta.ordem
        );
    };

    $scope.editarPergunta = function (pergunta, topico) {
        $scope.pergunta = angular.copy(pergunta);
        $scope.pergunta.topico = topico;

        var index = topico.perguntas.indexOf(pergunta);

        if(index > -1)
            topico.perguntas.splice(index, 1);
    };

    $scope.removerPergunta = function (pergunta, topico) {
        if(!confirm("Deseja realmente excluir esta pergunta?"))
            return;

        var index = topico.perguntas.indexOf(pergunta);

        if(index > -1)
            topico.perguntas.splice(index, 1);
    };

    $scope.adicionarTopico = function () {
        
        ModalService.showModal({
            templateUrl: PATH.postQuery({page: 'cpa_questionario', action: 'adicionarTopicoView'}),
            controller: "ModalAdicionarTopicoController"
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(topico) {
                if(angular.isDefined(topico))
                    $scope.formulario.topicos.push(topico);
            });
        });
        
    };

    $scope.verificarAdicionarQuestionario = function () {
        return !(
            angular.isUndefined($scope.formulario.id_tipo_usuario) ||
            $scope.formulario.nome == "" ||
            $scope.formulario.data_inicio == "" ||
            $scope.formulario.data_fim == ""
        );
    };

    $scope.adicionarQuestionario = function () {
        if(!$scope.verificarAdicionarQuestionario())
            return;

        QuestionarioService.postQuestionario($scope);
    };

    $scope.fecharQuestionario = function () {
        if(confirm("Ao fechar este questionário, toda as informções que não foram salvas serão perdidas! Deseja continuar?"))
            $location.path('/');
    };

    $scope.processarObjetoData = function(campo) {
        if(angular.isDefined(campo) && angular.isDate($scope.formulario[campo + "_objeto"]))
            $scope.formulario[campo] = $scope.formulario[campo + "_objeto"].toISOString().slice(0,10);
        else if(angular.isString($scope.formulario[campo + "_objeto"])){
            var dataArray = $scope.formulario[campo + "_objeto"].split("/");
            $scope.formulario[campo] = (new Date(dataArray[2], dataArray[1] - 1, dataArray[0])).toISOString().slice(0,10);
        }
    };

});

questionario.controller('ModalAdicionarTopicoController', function ($scope, QuestionarioService, close) {

    $scope.topico = QuestionarioService.getTopico();

    $scope.dismissModal = function(topico) {
        close(topico, 200); // close, but give 200ms for bootstrap to animate
    };

    $scope.adicionarTopico = function () {
        $scope.dismissModal($scope.topico);
    };
});

questionario.controller('QuestionarioAtualizarController', function ($scope) {
    //
});