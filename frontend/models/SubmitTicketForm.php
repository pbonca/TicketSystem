<?php
namespace frontend\models;

use common\models\Ticket;
use common\models\CommonUser;
use DateTime;
use Yii;

class SubmitTicketForm extends \yii\base\Model
{
    public $title;
    public $description;


    public function rules()
    {
        return[
          ['title', 'trim'],
          ['title', 'required'],
          ['title', 'string', 'min' => 2, 'max' => 255],

          ['description', 'trim'],
          ['description', 'required'],
          ['description', 'string'],
        ];
    }

    public function submit()
    {
        if($this->validate()) {
            $ticket = new Ticket();
            $this->fillTo($ticket);
            $ticket->save();
        }
    }

    public function fillTo(Ticket $ticket): Ticket
    {
        $ticket->title = $this->title;
        $ticket->description = $this->description;
        $ticket->author_id = Yii::$app->user->identity->getId();
        $ticket->modification_date = (new DateTime('+2 hours'))->format('Y-m-d H:i:s');

        return $ticket;
    }
}