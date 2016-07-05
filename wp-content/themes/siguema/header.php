<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">      
        <link type="text/css" rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" />
        <title>
            <?php
            bloginfo('name');
            if(is_home())
                echo ' - ' . get_bloginfo('description');
            else
                wp_title('|', true);
            ?>
        </title>
        
        <?php wp_head(); ?>
    </head>
    <body>

        <header class="container bg-blue colorWhite">
            <div class="content">
                <div class="header">
                    <h1>UEMA - SIGUEMA Acadêmico </h1>
                    <h3>Sistema Integrado de Gestão</h3>
                    <div class="logout">
                        <a href="#" title="Sair">SAIR</a>
                    </div>
                </div>
                <div class="info-user">
                    
                </div>
            </div>
        </header>
        
        <section class="cabecalho container">
            <div class="content info-user">
                <p><strong>USUÁRIO TESTE NTI</strong></p>
                <p>DPD/NTI/PROPLAN</p>
                <p class="periodo">Semestre atual: <strong>2016.1</strong></p>
            </div>
        </section>