<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageStoreRequest;
use App\Models\Image;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$images = Image::all();

        $images = Image::paginate(5);

        return response()->json([
            'images' => $images
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageStoreRequest $request)
    {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            Image::create([
                'name' => $request->name,
                'image' => $imageName,
                'description' => $request->description
            ]);

            // Save Image in Storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            // Return Json Response
            return response()->json([
                'message' => "Image post successfully created.",
                'image' => $imageName,
            ],200);

        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Image::find($id);
       if(!$image){
         return response()->json([
            'message'=>'Image Not Found.'
         ],404);
       }

       // Return Json Response
       return response()->json([
          'image' => $image
       ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(ImageStoreRequest $request, $id)
    {
        try {
            $image = Image::find($id);
            if(!$image){
              return response()->json([
                'message'=>'Image Not Found.'
              ],404);
            }

            $image->name = $request->name;
            $image->description = $request->description;

            if($request->image) {
                // Public storage
                $storage = Storage::disk('public');

                // Old iamge delete
                if($storage->exists($image->image))
                    $storage->delete($image->image);

                // Image name
                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $image->image = $imageName;

                // Image save in public folder
                $storage->put($imageName, file_get_contents($request->image));
            }

            $image->save();

            // Return Json Response
            return response()->json([
                'message' => "Image successfully updated.",
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // Detail
         $image = Image::find($id);
         if(!$image){
           return response()->json([
              'message'=>'Image Not Found.'
           ],404);
         }

         // Public storage
         $storage = Storage::disk('public');

         // Iamge delete
         if($storage->exists($image->image))
             $storage->delete($image->image);

         // Delete Image
         $image->delete();

         // Return Json Response
         return response()->json([
             'message' => "Image successfully deleted."
         ],200);
    }
}
