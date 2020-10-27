<?php

namespace App\Controller;


use App\Services\ChangeDate;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index (ChangeDate $changeDate)
    {
        $date = $changeDate->getOrderDate('799975');

        $changesPlus = $changeDate->setNewOrderDate('799975',$date['createdAt'],$date['data_order_change'],'+');

        $changesMinus = $changeDate->setNewOrderDate('799975',$date['createdAt'],$date['data_order_change'],'-');
         return new Response('<p>Текущая дата:'.$date['createdAt'].'</p>'.
                           '<p>Новая дата(+):'.$changesPlus['message'].'</p>'.
             '<p>Новая дата(-):'.$changesMinus['message'].'</p>'
         );
    }

}