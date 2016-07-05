<h3>Cadastro de Questionário</h3>
<form method="post" action="admin-post.php">
    <input type="hidden" name="idTipoUsuario" value="1"/>
    <input type="hidden" name="action" value="create"/>
    <input type="hidden" name="todo" value="questionario"/>
    <table>
        <tr>
            <td><label>Nome</label></td>
            <td><input type="text" name="nome"/></td>
        </tr>
        <tr>
            <td><label>Data Inicial</label></td>
            <td><input type="text" name="dataInicio"/></td>
        </tr>
        <tr>
            <td><label>Data Final</label></td>
            <td><input type="text" name="dataFim"/></td>
        </tr>
        <tr>
            <td><input type="submit" value="Salvar"/></td>
        </tr>
    </table>
</form>