<?php
namespace App\Actions\Blogs;

use App\Helpers\ImageDimensions;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Models\Blog;
use App\Implementations\BlogImplementation;
use App\Http\Resources\BlogResource;
use App\Actions\Translations\StoreTranslationAction;
use App\Actions\Uploads\UploadImageAction;
use Hash;
class StoreBlogAction
{
    use AsAction;
    use Response;
    private $blog;

    function __construct(BlogImplementation $BlogImplementation)
    {
        $this->blog = $BlogImplementation;
    }

    public function handle(array $data)
    {
        $data['photo'] = UploadImageAction::run($data['file'], ImageDimensions::BLOG_IMAGE);

        $blog = $this->blog->Create($data);

        foreach($data['languages'] as $key => $lang)
        {
            foreach($lang as $translationKey => $translation)
            {
                StoreTranslationAction::run([
                    'language_id' => $key,
                    'type' => Blog::class,
                    'type_id' => $blog->id,
                    'text_type' => $translationKey,
                    'value' => $translation,
                ]);
            }

        }
        return new BlogResource($blog);
    }
    public function rules()
    {
        return [
            'name' => ['required', 'unique:blogs,name'],
            'file' => ['required'],
            'languages' => ['required'],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request)
    {
        if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('blog.add'))
            return $this->sendError('Forbidden',[],403);

        $blog = $this->handle($request->all());

        return $this->sendResponse($blog,'');
    }
}
