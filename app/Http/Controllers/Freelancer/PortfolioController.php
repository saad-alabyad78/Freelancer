<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\File;
use App\Models\Image;
use App\Models\Portfolio;
use App\Models\Skillable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\IPortfolioRepository;
use App\Http\Resources\Freelancer\PortfolioResource;
use App\Http\Requests\Freelancer\CreatePortfolioRequest;
use App\Http\Requests\Freelancer\DeletePortfolioRequest;
use App\Http\Requests\Freelancer\UpdatePortfolioRequest;

/**
 *@group Freelancer Managment 
 *
 **/
class PortfolioController extends Controller
{

    public function __construct(protected IPortfolioRepository $portfolioRepository)
    {

    }
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function show(Portfolio $portfolio)
    {
        return PortfolioResource::make($portfolio->load(['skills', 'files', 'images']));
    }
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function store(CreatePortfolioRequest $request)
    {
        DB::beginTransaction();

        $data = $request->validated();

        try {

            $portfolio = $this->portfolioRepository->create($data);

            DB::commit();

            return PortfolioResource::make($portfolio->load(['skills', 'files', 'images']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'err' => $th,
                'message' => 'something went wrong',
                'error' => $th->getMessage(),
                'line' => $th->getLine()
            ], 400);
        }
    }
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function update(UpdatePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id,
            'freelancer_id' => auth()->user()->role['id']
        ])->first();

        if ($portfolio == null) {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ], 404);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();

            $portfolio = $this->portfolioRepository->update($portfolio, $data);
            
            DB::commit();

            return PortfolioResource::make($portfolio->load(['files', 'images', 'skills']));

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'err' => $th,
                'message' => 'something went wrong',
                'error' => $th->getMessage(),
                'line' => $th->getLine()
            ], 400);
        }

    }

    /**
     * Delete Portfolio.
     * 
     * @authenticated
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function delete(DeletePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id,
            'freelancer_id' => auth()->user()->role['id']
        ])->first();

        if ($portfolio == null) {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ], 404);
        }

        $portfolio->delete();

        return response()->json(['message' => 'deleted']);
    }
}
