<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
class ImageController extends Controller
{
    public function create(Request $request){
        \Log::info('Imágenes recibidas:', $request->all());

        if ($request->hasFile('image')) {
            $uploadedFiles = $request->file('image');
            $savedImages = [];
    
            // Verificamos si `$uploadedFiles` es un array o un solo archivo
            if (!is_array($uploadedFiles)) {
                $uploadedFiles = [$uploadedFiles]; // Convertimos a array si es solo un archivo
            }
    
            foreach ($uploadedFiles as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
    
                $imageModel = new Image();
                $imageModel->CODACTUAL = $request->codactual;
                $imageModel->nombre = '/images/' . $imageName;
                $imageModel->save();
    
                $savedImages[] = $imageModel->nombre;
            }
    
            return response()->json([
                'message' => 'Imágenes subidas correctamente',
                'imagenes' => $savedImages
            ]);
        } else {
            return response()->json(['error' => 'No se ha proporcionado una imagen'], 400);
        }
    
    }
    public function show($id){
        $urls = Image::where('CODACTUAL', $id)->pluck('nombre')->toArray();
        return response()->json($urls);
    }
}
