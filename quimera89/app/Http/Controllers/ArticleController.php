<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;

use Auth;
use DB;

use Illuminate\Http\Request;
use App\Articulos;

class ArticleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
		public function __construct()
	{
		$this->middleware('auth');
	}
	
	 
	 
	public function index()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('articles.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ArticleRequest $request)
	{
		$input =  $request->all();
		$input['idusuario'] = Auth::id();
		Articulos::create($input);
		return redirect('articles/showArticles');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	    $data = [];
		$resultados = Articulos::where('idusuario', '=', Auth::id())->paginate(10);
		$data['resultados'] = $resultados;
		
		return view('articles.show',$data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = [];	
		$articulo = Articulos::findOrFail($id);
		$data['articulo'] = $articulo;
		return view('articles.edit',$data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,ArticleRequest $request)
	{
		$input = Articulos::findOrFail($id);	
		$input -> update($request -> all());
		return redirect('articles/showArticles');	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
