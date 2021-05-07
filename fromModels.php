
   <?php

   // dalis trianguliacijos codo  atstumui nustatyti tarp pasirinkto miesto ir maršruto lokacijos.
    static function degreesToRadians($degrees) {
        return $degrees *pi() / 180;
      }
      
      static function distanceInKmBetweenEarthCoordinates($lat1, $lon1, $lat2, $lon2) {
         $earthRadiusKm = 6371;
      
         $dLat = Route::degreesToRadians($lat2-$lat1);
         $dLon = Route::degreesToRadians($lon2-$lon1);
      
        $lat1 = Route::degreesToRadians($lat1);
        $lat2 = Route::degreesToRadians($lat2);
      
         $a = sin($dLat/2) * sin($dLat/2) +
                sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
         $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
        return $earthRadiusKm * $c;
      }


      // eilinis metodas išsaugoti duomenis, sutvarkyta telefonų numerių formatas

      public static function savePerson($request){
        $person = new Contact_person;
        $person->purpose = $request->purpose;
        $person->name = $request->name;
        $person->surname = $request->surname;
        $pn = $request->phone;
        if(substr($pn,0,1)== "+" || substr($pn,0,1) == "8" || substr($pn,0,1) == "3"){
            $pn = substr($pn,strlen($pn)-8,8);
        }
        $person->phone = $pn;
        $person->email = $request->email;
        $person->user_id = $request->id;
        $person->save();
    }


    // some random code
    public static function updateShop($request){
      $shop = new Shop;
      $ar = ['before','mid','after'];
      $ar1 = [$request->shop1,$request->shop2,$request->shop3];
      for ($i=0; $i <3 ; $i++) { 
        if(isset($ar1[$i])){
            $shop->updateSave($ar[$i], 1, $request);
            }else{
                $shop->updateSave($ar[$i], 0, $request);
            }
      }        
    }
    public function  updateSave($what, $withWhat, $request){
        DB::table('shops')->where('route_id','=',$request->route_id)->update([$what =>$withWhat]);
         
        }
      ?>