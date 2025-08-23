<template>
    <h2 class="mt-3">Génération de rapport</h2>

    <div class="mt-4 d-flex flex-column col-md-4">
        <h5>Nom du rapport </h5>
        <input type="text" name="" id="" class="form-control form-control-lg">
    </div>

    <div class="mt-4 d-flex flex-column col-md-4">
        <h5>Type de rapport </h5>
        <select class="form-select form-select-lg md-1">
            <option value="1">Résumé</option>
            <option value="2">KPI</option>
        </select>
    </div>

     <div class="mt-4 d-flex flex-column col-md-4">
        <h5>Période </h5>
        <div class="form-check">
            <input class="form-check-input form-check-input-lg" type="radio" name="exampleRadios" id="radio1" value="option1" checked>
            <label class="form-check-label form-check-label-lg" for="radio1">
                Journalière
            </label>
        </div>
        
        <div class="form-check">
            <input class="form-check-input form-check-input-lg" type="radio" name="exampleRadios" id="radio2" value="option2">
            <label class="form-check-label form-check-label-lg" for="radio2">
                Hebdomadaire
            </label>
        </div>
        
        <div class="form-check">
            <input class="form-check-input form-check-input-lg" type="radio" name="exampleRadios" id="radio3" value="option3">
            <label class="form-check-label form-check-label-lg" for="radio3">
                Mensuelle
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input form-check-input-lg" type="radio" name="exampleRadios" id="radio3" value="option3">
            <label class="form-check-label form-check-label-lg" for="radio3">
                Annuelle
            </label>
        </div>

    </div>

    <h4 class="mt-5">Description :</h4>
    <div class="col-md-5 d-flex flex-column gap-4 mt-3">
        <input type="text" class="form-control"style="height: 100px;">
        <div class="d-flex w-100 justify-content-between">
            <button type="button" class="btn btn-secondary btn-lg" style="width: 45%;">Annuler</button>
            <button type="button" class="btn btn-primary btn-lg" style="width: 45%;">Créer</button>
        </div>
    </div>

</template>