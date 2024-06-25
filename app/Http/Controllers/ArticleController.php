<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\MacroCategory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function articleView(){
        return view('articles');
    }
    public function create(){
        return view('article.create');
    }

    public function index(){
        $articles = Article::where('is_accepted', true)->orderBy('created_at','desc')->paginate(6);
        return view('article.index',compact('articles'));
    }

    public function show(Article $article){
        return view('article.show',compact('article'));
    }

    public function byCategory(Category $category){
        $article = $category->articles->where('is_accepted', true);
        return view('article.byCategory', compact('articles', 'category'));
    }

    public function byMacroCategory($macroCategoryId){
        
        $macroCategory = MacroCategory::findOrFail($macroCategoryId);

        // Trova tutte le categorie che appartengono a questa macrocategoria
        $categories = Category::where('macroCategory_id', $macroCategoryId)->get();
        
        // Trova tutti gli articoli che appartengono a queste categorie
        $articles = Article::whereIn('category_id', $categories->pluck('id'))->get();

        return view('article.byMacroCategory', [
            'macroCategory' => $macroCategory,
            'articles' => $articles,
        ]);
    }
}

