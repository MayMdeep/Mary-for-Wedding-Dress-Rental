<?php
namespace App\Actions\Blogs;

use App\Actions\Translations\UpdateTranslationAction;
use App\Actions\Uploads\UploadImageAction;
use App\Helpers\ImageDimensions;
use App\Http\Resources\BlogResource;
use App\Implementations\BlogImplementation;
use App\Models\Blog;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBlogAction
{
    use AsAction;
    use Response;
    private $blog;

    function __construct(BlogImplementation $BlogImplementation)
    {
        $this->blog = $BlogImplementation;
    }

    public function handle(array $data, int $id)
    {
        if (array_key_exists('file', $data)) {
            $data['photo'] = UploadImageAction::run($data['file'], ImageDimensions::BLOG_IMAGE);
        }

        if (array_key_exists('languages', $data)) {
            foreach ($data['languages'] as $key => $lang) {
                foreach ($lang as $translationKey => $translation) {
                    UpdateTranslationAction::run([
                        'text_type' => $translationKey,
                        'value' => $translation,
                    ], (int) $key);
                }

            }
        }

        $blog = $this->blog->Update($data, $id);
        return new BlogResource($blog);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['unique:blogs,name,' . $request->route('id')],
        ];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(Request $request, int $id)
    {

        if (auth('sanctum')->check() && !auth('sanctum')->user()->has_permission('blog.edit')) {
            return $this->sendError('Forbidden', [], 403);
        }

        $blog = $this->handle($request->all(), $id);

        return $this->sendResponse($blog, '');
    }
}
