<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Ticket
 * @package Source\Models
 * MODELO DE TICKET - DESENVOLVIDO POR ADAM ALMEIDA
 * PROCESSO TROC 2021
 */
class Ticket extends Model
{
    /*** @var */
    protected $message;

    /**
     * Ticket constructor.
     */
    public function __construct()
    {
        parent::__construct("tickets", ['id'], ['ticket_code', 'due_date', 'value']);
    }

    /**
     * MÉTODO DE INCIALIZAÇÃO/ALIMENTAÇÃO DO OBJETO
     * @param string $ticket_code
     * @param string $due_date
     * @param string $value
     * @param string $type
     * @return $this|null
     */
    public function bootstrap(string $ticket_code, string $due_date, string $value, string $type = "s"): ?Ticket
    {
        $this->ticket_code = $ticket_code;
        $this->due_date = $due_date;
        $this->value = $value;
        $this->type = $type;
        return $this;
    }

    /**
     * MÉTODO DE ATUALIZAÇÃO DO TICKET
     * @param array $data
     * @param int $idTicket
     * @return bool
     */
    public function ticketUpdate(array $data, int $idTicket): bool
    {
        try {

            if (!$this->findById($idTicket)) {
                return false;
            }

            $this->update($data, "id = :id", "id={$idTicket}");
            $this->save();

            $this->message("success", "Ticket atualizado com sucesso");
            return true;

        } catch (PDOException $exception) {
            $this->message("warning", $exception);
            return false;
        }
    }

    /**
     * MÉTODO DE EXCLUSÃO DE TICKET
     * @param int $id
     * @return bool
     */
    public function ticketDestroy(int $id): bool
    {
        try {

            if (!$this->findById($id)) {
                return false;
            }

            $this->delete("id = :id", "id={$id}");
            $this->message("success", "Ticket excluido com sucesso");
            return true;

        } catch (PDOException $exception) {
            $this->message("warning", $exception);
            return false;
        }
    }

    /**
     * MÉTODO PARA O CÁLCULO DO DESCONTO
     * @param int $idProduct
     * @param string $ticket
     * @return float|null
     */
    public function addDiscount(int $idProduct, string $ticket): ?float
    {

        $productValue = (new Product())->findById($idProduct);
        $ticketDiscount = (new Ticket())->find("ticket_code=:t", ":t={$ticket}")->fetch();


        if (!$productValue || !$ticketDiscount) {
            return null;
        }

        if ($ticketDiscount->type == "v") {
            return $discount = (float)$productValue->value - (float)$ticketDiscount->value;
        } else {
            return $discount = (float)$productValue->value - ((float)$productValue->value / 100) * (float)$ticketDiscount->value;
        }

    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $type
     * @param string $text
     * @return object
     */
    public function message(string $type, string $text): object
    {
        return $this->message = (object)[
            "type" => $type,
            "text" => $text
        ];
    }


}