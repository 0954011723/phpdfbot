<?php

namespace App\Http\Controllers;

use App\Contracts\Validation\CreateUpdateInterface;
use Illuminate\Http\Response;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ApiController
 *
 * @author Marcelo Rodovalho <rodovalhomf@gmail.com>
 */
class ApiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $this->repository->pushCriteria(app(RequestCriteria::class));
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUpdateInterface $request
     *
     * @return Response
     *
     * @throws ValidatorException
     */
    protected function storeDefault(CreateUpdateInterface $request): Response
    {
        $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

        $data = $this->repository->create($request->all());

        return response()->created(
            'Data stored.',
            $data['data']
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateUpdateInterface $request
     * @param string                $id
     *
     * @return Response
     *
     * @throws ValidatorException
     */
    protected function updateDefault(CreateUpdateInterface $request, $id): Response
    {
        $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

        $data = $this->repository->update($request->all(), $id);

        return response()->success(null, $data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id): Response
    {
        return $this->repository->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id): Response
    {
        $deleted = $this->repository->delete($id);
        return response()->success('Successfully deleted!', $deleted);
    }
}
