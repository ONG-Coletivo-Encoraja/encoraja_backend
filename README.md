# Site WEB Encoraja 

Esse repositório contém o desenvolvimento backend do Projeto Integrador do Tecnólogo em Análise e Desenvolvimento de Sistemas, para o centro universitário Cesumar. 

A Organização não Governamental (ONG) **Coletivo Encoraja** apoia mulheres em situação de vulnerabilidade. Atualmente, a divulgação das suas atividades é feita somente através de redes sociais e o cadastro no Google Forms. Este projeto visa aprimorar o marketing do Coletivo Encoraja e desenvolver uma aplicação web para centralizar informações e serviços. A plataforma facilitará cadastros, elaboração de relatórios e gerenciamento de eventos e projetos. O objetivo é criar uma ferramenta digital integrada que amplie o alcance e o impacto da ONG, ajudando mais mulheres a obter apoio e segurança.

## Rodando o projeto
- Criar o database
- ```composer install```: para instalação das dependências
- ```.ENV```: criar e configurar o arquivo .ENV com as informações do banco e do servidor de email (sugestão: MailTrap)
- ```php artisan jwt:secret```: para gerar a chave privada do JWT
- ```php artisan key:generate```: gerar chave do banco
- ```php artisan migrate```: para criar as tabelas
- ```php artisan db:seed```: para popular o banco
- ```php artisan serve```: para o servidor PHP



## Equipe

<div style="text-align: center;">
    <table style="margin: 0 auto;">
        <tr>
            <td style="text-align:center;">
                <img src="https://media.licdn.com/dms/image/v2/D4D03AQGjkE_TgqbwKQ/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1718147518615?e=1733961600&v=beta&t=ZciuQR7qrvzZNgNxK8kO353gb2u68rZjf6BYT60bW2Y" alt="Andre">
                <br>
                Andre
            </td>
            <td style="text-align:center;">
                <img src="https://media.licdn.com/dms/image/v2/D4D03AQHr7w0DU_lM6A/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1710427543029?e=1733961600&v=beta&t=TScPZk5npsOXdvX34o09o7RfvC3Y3Ri56BjqvE_eDyM" alt="Juliana">
                <br>
                Juliana
            </td>
            <td style="text-align:center;">
                <img src="https://media.licdn.com/dms/image/v2/D4D03AQFA7-k5CkqfNA/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1724609225442?e=1733961600&v=beta&t=6RTp513BC9FGoEkwvDFS2Ycb5Mp4L7sL_v6bAE3Pk4U" alt="Maria">
                <br>
                Maria
            </td>
        </tr>
    </table>
</div>
