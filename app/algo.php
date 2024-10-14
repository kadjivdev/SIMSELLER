// 

#### ALGO POUR EQUILIBRER LES QTE PROGRAMMER & QTE LIVRER
$programmations = Programmation::whereNotNull("qtelivrer")->orderBy("id","desc")->get();
        // $data = [];

        // foreach ($programmations as $programmation) {
        //     if (($programmation->qteprogrammer!=$programmation->qtelivrer) && ($programmation->qteprogrammer >= $programmation->vendus->sum("qteVendu")) ) {
        //         $data[]=[
        //             "qteprogrammer"=>$programmation->qteprogrammer,
        //             "qtelivrer"=>$programmation->qtelivrer,
        //             "qteVendu"=>$programmation->vendus->sum("qteVendu"),
        //         ];

        //         $programmation->update(["qtelivrer"=>$programmation->qteprogrammer]);
        //     }
        // }