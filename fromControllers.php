<?php
  public static function getUser(){
    $user=[];
    $user['contact_people'] = DB::table('contact_people') ->where('user_id','=',Auth::user()->id)->get();
    $user['emails'] = DB::table('contact_emails') ->where('user_id','=',Auth::user()->id)->get();
    $user['urls'] = DB::table('user_urls') ->where('user_id','=',Auth::user()->id)->get();
    $user['facebooks'] = DB::table('facebooks') ->where('user_id','=',Auth::user()->id)->get();
    return $user;
    }

    public static function getAll(){
        return Route::where('user_id','=',Auth::user()->id)->inrandomorder()->get();
    }

    public static function getAllAllIds(){
        return Route::where('status','=',2)->inrandomorder()->pluck('id')->toArray();
    }

    public static function getAllAll(){
        return Route::with('shops')->with('parkings')->with('Sleeps')->
        with('WaterRelations.waters')->with('photos')->
        with('sleeps')->with('routeDisplayeds')->with('routeVisiteds')->
        where('status','=',2)->inrandomorder()->get();;
    }


//--------------------
    public function index()
    {
        session_start();

        $shownRoutes =  $_SESSION['routesToShowCount'] = 0;// jei užėjai į home, nuresetinam i 0, kad rodytume nuo pirmo route
        $allRoutesIds = HomeController::getRoutesIdsSortByDay(); //pasiemu routes id pagal dienos sorta      
        $_SESSION['allRoutesIds'] = $allRoutesIds;

        $session = [];
        if(isset($_SESSION['routes'])){
            $session = $_SESSION['routes'];
        }
        $rivers = Water::orderBy("water")->get(); 
        if($allRoutesIds==0){
            return view('home2', ['routes'=>[],'rivers'=>$rivers,'routesSession'=>$session]);
     
        }
        $arr1 = array_slice($allRoutesIds,$shownRoutes,10); //paimu pirmus 10 id
        $routes10 = Route::findMany($arr1);//pagal id išsitraukiu pirmus 10 routes 
     
        //-------------------------------- bulk saving route displayed 
        if($arr1!=[]){
            $now = Carbon::now();
            $query = "INSERT INTO `route_displayeds` (`id`, `route_id`, `created_at`, `updated_at`) VALUES";
            for ($i=0; $i <count($arr1) ; $i++) { 
                $query .=   "(NULL, '".$arr1[$i]."', '".$now."', '".$now."')";
                if(count($arr1)-1>$i){
                    $query .=",";  
                }
            }
            $stmt = DB::getPdo()->prepare($query);
            $success = $stmt->execute();
        }
            //--------------------------------
            $_SESSION['routesToShowCount']=10;

            
            return view('home2', ['routes'=>$routes10,'rivers'=>$rivers,'routesSession'=>$session]);
        }

//-----------------
public function store(Request $request)
{
 


    $validator = Validator::make($request->all(),
    [
        'km' => ['required','numeric','min:1','max:1000'],
        'h' => ['required','numeric','min:1','max:10000'],
        'd' => ['required','numeric','min:1','max:100'],
        'coast' => ['required'],
        'prolong' => ['required'],
        'clear_road' => ['required'],
        'photos[]' => ['mimes:jpg,bmp,png'],
        'file' => ['max:50120'],
        'attachments' => ['max:3'],
        'photos.*' => ['mimes:jpeg,jpg,png,gif','max:5120'],
    ],
    [
    'photos.*.mimes' => '*Vienas iš failų nėra nuotrauka.',
    'photos.max' => '*Galite turėti ne daugiau 10 nuotraukų.',
    'photos.*.max' => '*Viena nuotrauka turi neviršyti 5MB.',
    'photos.image' => '*Visi failai turi būti nuotraukos',
    'file' => '*Nuotraukos dydis turi neviršyti 5MB  ',

    'km.required' => '*Prašome pateikti maršruto ilgį: kilometrais.   ',
    'h.required' => '*Prašome pateikti maršruto ilgį: plaukimo valandomis.   ',
    'd.required' => '*Prašome pateikti maršruto ilgį: dienomis.   ',
    
     'prolong.required' => '*Informacija apie galimybę pratesti kelionę privaloma.   ',
     'coast.required' => '*Informacija apie kranto patogumą privaloma.   ',
     'clear_road.required' => '*Informacija apie maršruto sunkumą privaloma.   ',

     'km.numeric' => '*Ilgis kilometrais: parašykite tik skaičiais.   ',
     'h.numeric' => '*Ilgis valandomis: parašykite tik skaičiais.   ',
     'd.numeric' => '*Kiek dienų plaukti: parašykite tik skaičiais.   ',

     'km.min' => '*Maršrutas turi būti bent 1km ilgio   ',
     'h.min' => '*Maršrutas turi būti bent 1 valandos trukmės   ',
     'd.min' => '*Maršrutas turi tilpti bent 1 dieną ar jos dalį   ',

     'km.max' => '*Maršrutas negali būti ilgesnis nei 1000km ilgio   ',
     'h.max' => '*Maršrutas negali būti ilgesnis nei 10000h plaukimo   ',
     'd.max' => '*Maršrutas negali būti ilgesnis nei 100 dienų   '
    ]);

    if ($validator->fails()) {
        $request->flash();
        return redirect()->back()->withErrors($validator);
    }

   
    $r=$request;
    if($r->km!=null || $r->h!=null||$r->d!=null){
        $route_id = Route::saveRoute($r);
        Shop::saveShop($r,$route_id);
        Parking::saveParking($r,$route_id);
        Sleep::saveSleep($r,$route_id);
        Water_relation::saveWater($r,$route_id);
      
    }
    
        if ($request->has('photos')) {
            foreach ($request->file('photos') as  $photo) {
            $img = Image::make($photo); //bitu kratinys
            $image = $photo; //failas ir jo info
            $fileName = Str::random(5).'.jpg';// random name
            $folder = public_path('img/routes');     
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($folder.'/'.$fileName, 80, 'jpg');
            
           Photo::savePhoto($route_id,$fileName);
            }
        }

        return redirect()->route('route.index')->with('success_message','Akcija sekmingai prideta');

}


?>