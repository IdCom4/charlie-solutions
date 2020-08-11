<?php

namespace App\Http\Controllers;

// Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

// Built-in includes
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Project includes
use App\Objects\Enums\{TypesEnum, FormFieldsEnum, MatFieldsEnum, MatStatusEnum};
use App\Objects\Traits\RequestHandler;
use App\Objects\{Material, LightMaterial};
use App\Models\{Charlie_materials_filiale, Charlie_type, Charlie_site};


class MaterialsController extends Controller
{
    use RequestHandler;

    // function that return an array filled with the requested entries
    function list(Request $request) {

        // fetching the required params with the RequestHandler trait getQueryParam function, that garantee to get a valid result
        $page = $this->getQueryParam($request, TypesEnum::_INT, FormFieldsEnum::_PAGE, 0, 0);
        $offset = $this->getQueryParam($request, TypesEnum::_INT, FormFieldsEnum::_OFFSET, FormFieldsEnum::_DEF_OFFSET, 1, 100);

        $filialeNum = $this->getQueryParam($request, TypesEnum::_INT, FormFieldsEnum::_FILIALE, 1, 1, 3);
        $keywords = explode(';', $this->getQueryParam($request, TypesEnum::_STRING, FormFieldsEnum::_KEYWORDS));
        $sortBy = $this->getQueryParam($request, TypesEnum::_STRING, FormFieldsEnum::_SORT_BY, MatFieldsEnum::_DEFAULT);
        $order = $this->getQueryParam($request, TypesEnum::_STRING, FormFieldsEnum::_ORDER, MatFieldsEnum::_ASCENDING);

        // checking if the user asked a right sorting field, setting it with his default value if not
        if (!in_array($sortBy, MatFieldsEnum::_LIGHT_AS_TAB))
            $sortBy = MatFieldsEnum::_DEFAULT;

        // checking if the user asked a right ordering field, setting it with his default value if not
        if ($order != MatFieldsEnum::_ASCENDING && $order != MatFieldsEnum::_DESCENDING)
            $order = MatFieldsEnum::_ASCENDING;

        // processing the keywords in a usable form, without spaces at the beginning or the end, in an array 
        $wordsCount = count($keywords);
        for($i = 0; $i < $wordsCount; $i++) {
            $keywords[$i] = trim($keywords[$i]);
        }

        // setting up the db model
        $dbMaterials = new Charlie_materials_filiale();
        $dbMaterials->setTable('charlie_materials_filiale' . $filialeNum);

        // fetching the requested entries from the db, using the params
        $rawMaterials = $dbMaterials->select(
            MatFieldsEnum::_DEFAULT,
            MatFieldsEnum::_IDENT,
            MatFieldsEnum::_TYPE,
            MatFieldsEnum::_REGION,
            MatFieldsEnum::_SITE,
            MatFieldsEnum::_STATUS
        )->where(function ($query) use ($keywords, $sortBy) {    
            foreach ($keywords as $kw) {
                $query->where(function ($query) use ($kw) {
                    $query->where(MatFieldsEnum::_IDENT, "like", "%{$kw}%")
                        ->orWhere(MatFieldsEnum::_TYPE, "like", "%{$kw}%")
                        ->orWhere(MatFieldsEnum::_REGION, "like", "%{$kw}%")
                        ->orWhere(MatFieldsEnum::_SITE, "like", "%{$kw}%")
                        ->orWhere(MatFieldsEnum::_STATUS, "like", "%{$kw}%");
                });
            }
            $query->whereNotNull($sortBy);
        })->skip($offset * $page)->take($offset)->orderBy($sortBy, $order)->get();
        
        // retrieving the materials type's id to fetch their name from the db
        $matIds = array();
        $i = 0;
        foreach($rawMaterials as $rawMaterial) {
            $matIds[$i++] = $rawMaterial->id_mat;
        }

        // fetching their names (of the material types) from the db
        $matTypes = Charlie_type::select('id', 'name')->whereIn('id', $matIds)->get();

        // building the LightMaterials objects array with the materials and the material types
        $lightMaterials = array();
        $i = 0;
        foreach($rawMaterials as $rawMaterial) {
            foreach($matTypes as $matType) {
                if ($matType->id == $rawMaterial->id_mat) {
                    $lightMaterials[$i++] = new LightMaterial($rawMaterial, $matType->name);
                    break;
                }
            }
        }

        // returning the corresponding view with the requested datas
        return view('materials', compact('lightMaterials'));
    }

    // function that delete a db entry
    function destroy($filiale, $id) {

        // checking if route params, necessary to identify the entry to delete, are here and well formated
        Validator::make([FormFieldsEnum::_FILIALE => $filiale, MatFieldsEnum::_DEFAULT => $id], [
            FormFieldsEnum::_FILIALE => "required|numeric|min:1|max:3",
            MatFieldsEnum::_DEFAULT => "required|numeric|min:1"
        ])->validateWithBag('routeParams');
        // stopping here if not, with an auto redirect and an error

        // setting up the db model
        $dbMaterials = new Charlie_materials_filiale();
        $dbMaterials->setTable('charlie_materials_filiale' . $filiale);

        // attempt to delete the entry
        $deleted = $dbMaterials->where(MatFieldsEnum::_DEFAULT, '=', $id)->delete();

        // checking if it was successfully deleted, and setting the session's data dependently
        if ($deleted)
            Session::flash("success", "Le matériel a bien été supprimé");
        else
            Session::flash("error", "La suppression du matériel a échoué, réessayez plus tard");

        // redirecting to the materials view
        return redirect('materials');
    }

    // function that request a specific entry to update and it's data
    function edit() {

        // checking if request params, necessary to identify the entry to delete, are here and well formated
        request()->validate([
            FormFieldsEnum::_FILIALE => "required|numeric|min:1|max:3",
            MatFieldsEnum::_DEFAULT => "required|numeric|min:1"
        ]);

        // storing them in variables
        $matFiliale = request(FormFieldsEnum::_FILIALE);
        $matId = request(MatFieldsEnum::_DEFAULT);

        // setting up the db model
        $dbMaterials = new Charlie_materials_filiale();
        $dbMaterials->setTable('charlie_materials_filiale' . $matFiliale);

        // fetching the requested entry
        $rawMaterial = $dbMaterials->select()->where(MatFieldsEnum::_DEFAULT, '=', $matId)->first();

        // checking if it was found, and redirecting with an error if not
        if (is_null($rawMaterial)) {
            Session::flash("error", "Filiale ou id incorrecte, matériel introuvable");
            return redirect()->back();
        }

        // getting the material type of the entry from the db
        $matType = Charlie_type::select('name')->where('id', '=', $rawMaterial->id_mat)->first();

        // building a Material object with the entry and the material type
        $material = new Material($rawMaterial, $matType->name);

        // fetching the sites from the db to allow the user to update the entry's one
        $sites = Charlie_site::select('id', 'name')->get();

        // returning the edit view with the requested material, the sites, and the correspondant filiale num
        return view('edit', compact('material', 'sites', 'matFiliale'));
    }

    // function that update the requested entry's datas in the db, based on the params
    function update($filiale, $id) {

        // checking if route params, necessary to identify the entry to delete, are here and well formated
        Validator::make([FormFieldsEnum::_FILIALE => $filiale, MatFieldsEnum::_DEFAULT => $id], [
            FormFieldsEnum::_FILIALE => "required|numeric|min:1|max:3",
            MatFieldsEnum::_DEFAULT => "required|numeric|min:1"
        ])->validateWithBag('routeParams');
        // stopping here if not, with an auto redirect and an error;

        // checking if request params, necessary to identify the entry to delete, are here and well formated
        request()->validate([
            MatFieldsEnum::_IDENT => "required",
            MatFieldsEnum::_TYPE => "required",
            MatFieldsEnum::_STATUS => ["required", Rule::in(MatStatusEnum::_AS_TAB)],
            MatFieldsEnum::_ID_CHANT => "nullable|numeric|min:1",
            MatFieldsEnum::_ID_Z_S => "nullable|numeric|min:1",
            MatFieldsEnum::_LOST => "nullable|date",
            MatFieldsEnum::_PRICE => "nullable|numeric|min:0"
        ]);

        // checking if a site is specified, to check it's validity if so
        $site = request(MatFieldsEnum::_SITE);
        if (!is_null($site)) {
            // getting it from the db
            $site = Charlie_site::select('id', 'name')->where('name', '=', $site)->first();

            // and checking it found it, redirecting with the correspondant error if not
            Validator::make([MatFieldsEnum::_SITE => $site], [
                MatFieldsEnum::_SITE => "required"
            ])->validate();
        }

        // prebuilding the query by storing the params to update in a well formated array
        $paramsToUpdate = array();
        foreach (request()->all() as $key => $value) {
            // for each param to update, i check in the enum tab if it can be and store only those which can
            if (in_array($key, MatFieldsEnum::_FILLABLE_AS_TAB))
                $paramsToUpdate[$key] = $value;
        }
    
        // setting up the db
        $dbMaterials = new Charlie_materials_filiale();
        $dbMaterials->setTable('charlie_materials_filiale' . $filiale);

        // trying to update the entry
        $updated = $dbMaterials->where(MatFieldsEnum::_DEFAULT, '=', $id)->update($paramsToUpdate);

         // checking if it was successfully updated, and setting the session's data dependently
        if ($updated)
            Session::flash("success", "Le matériel a bien été mis à jour");
        else
            Session::flash("error", "La mise à jour du matériel a échoué, réessayez plus tard");
        
        // redirecting the edit view
        return redirect()->back();
    }
}
