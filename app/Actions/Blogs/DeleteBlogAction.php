<?php
namespace App\Actions\Blogs;

use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use App\Traits\Response;
use App\Implementations\BlogImplementation;
use Hash;
class DeleteBlogAction
{
    use AsAction;
    use Response;
    private $blog;
    
    function __construct(BlogImplementation $BlogImplementation)
    {
        $this->blog = $BlogImplementation;
    }

    public function handle(int $id)
    {
        return $this->blog->delete($id);
    }
    public function rules()
    {
        return [];
    }
    public function withValidator(Validator $validator, ActionRequest $request)
    {
    }

    public function asController(int $id)
    {
        try{
    
            if(auth('sanctum')->check() &&  !auth('sanctum')->user()->has_permission('blog.delete'))
            return $this->sendError('Forbidden',[],403);
            $this->handle($id);
            return $this->sendResponse(['Success'], 'Blog Deleted successfully.');
        
		}catch (\Exception $e) {
            return $this->sendError($e->getMessage());
			
		}
    }
}