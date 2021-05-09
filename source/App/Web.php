<?php

namespace Source\App;

use League\Plates\Engine;
use Source\Models\Auth;
use Source\Models\Product;
use Source\Models\Ticket;

/**
 * Class Web
 * @package Source\App
 * CONTROLADOR WEB - DESENVOLVIDO POR ADAM ALMEIDA
 * PROCESSO TROC 2021
 */
class Web
{
    /**@var Engine */
    private $view;

    /**
     * Web constructor.
     */
    public function __construct()
    {
        $this->view = Engine::create(__DIR__ . "/../../theme/", "php");
    }

    /**
     *MÉTODO DE EXIBIÇÃO DA HOME
     */
    public function home(): void
    {
        $tickets = (new Ticket())->find()->fetch(true);
        $products = (new Product())->find()->fetch(true);

        echo $this->view->render("home", [
            "title" => "HOME | PROCESSO TROC",
            "tickets" => $tickets,
            "products" => $products
        ]);
    }

    /**
     * @param array|null $data
     */
    public function login(?array $data): void
    {
        if (!empty($data['csrf'])) {

            if (empty($data['email']) || empty($data['password'])) {

                $text = ("Preencha todos os campos" ?? null);
                $type = ("warning" ?? null);

                echo $this->view->render("login", [
                    "title" => "LOGIN | PROCESSO TROC",
                    "message" => [
                        "text" => $text,
                        "type" => $type
                    ]
                ]);
                return;
            }

            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password']);

            if ($login){
                redirect("/admin/dash");
            }

            $messageAuth = $auth->getMessage();

            echo $this->view->render("login", [
                "title" => "LOGIN | PROCESSO TROC",
                "message" => [
                    "text" => $messageAuth->text,
                    "type" => $messageAuth->type
                ]
            ]);
            return;

        }

        echo $this->view->render("login", [
            "title" => "LOGIN | PROCESSO TROC",
            "message"
        ]);
    }

    /**
     * @param array $data
     * MÉTODO PARA EXIBIÇÃO DOS PRODUTOS
     */
    public function product(array $data): void
    {
        //** VALIDAÇÃO DE ID */

        if (!filter_var($data['id'], FILTER_VALIDATE_INT) || '') {
            redirect("/");
            die();
        }

        $product = (new Product())->findById($data['id']);


        //** VALIDAÇÃO SE EXISTEM RESULTADOS */
        /* REFATORAR :: IMPLEMENTAR A CLASSE ->  MESSAGE */

        if (!empty($product)) {

            echo $this->view->render("product", [
                "title" => $product->name . " | PROCESSO TROC",
                "product" => $product
            ]);
        } else {
            redirect("/");
        }
    }

    /**
     * @param array $data
     * MÉTODO DE APLICAÇÃO E VALIDAÇÃO DO TICKET DE DESCONTO
     */
    public function appTicket(array $data): void
    {
        //** VALIDAÇÃO DE ID DO PRODUTO A SER APLICADO */
        if (!filter_var($data['id'], FILTER_VALIDATE_INT) || '') {
            redirect("/");
            die();
        }

        $product = (new Product())->findById($data['id']);

        //** FILTRO DA ENTRADA DE DADOS PELO FORMULÁRIO */
        $postTicket = (filter_input(INPUT_POST, 'ticket', FILTER_SANITIZE_SPECIAL_CHARS));


        if (!empty($postTicket)) {

            /* REFATORAR :: IMPLEMENTAR A CLASSE ->  MESSAGE */
            /* APLICANDO O MÉTODO DO DESCONTO E RETORNANDO O NOVO VALOR */
            $discount = (new Ticket())->addDiscount($product->id, $postTicket);

            echo $this->view->render("product", [
                "title" => $product->name . " | PROCESSO TROC",
                "product" => $product,
                "discount" => $discount
            ]);
        }else{

            echo $this->view->render("product", [
                "title" => $product->name . " | PROCESSO TROC",
                "product" => $product,
            ]);

        }

    }

    /**
     * @param $data
     */
    public function error($data): void
    {
        redirect("/");
    }
}