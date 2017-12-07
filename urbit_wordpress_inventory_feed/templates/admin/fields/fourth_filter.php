<div class="row">
    <div class="col-5">
        <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
            <?php foreach ($elements['products'] as $product): ?>
                <option value="<?= $product->get_id() ?>"><?= $product->get_id() . ' - ' . $product->get_name() ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-2 filter-buttons">
        <button type="button" id="search_rightAll" class="btn btn-dark">Select all</button>
        <button type="button" id="search_rightSelected" class="btn btn-dark">Select</i></button>
        <button type="button" id="search_leftSelected" class="btn btn-dark">Remove</i></button>
        <button type="button" id="search_leftAll" class="btn btn-dark">Remove all</i></button>
    </div>

    <div class="col-5">
        <select name="<?= $name ?>" id="search_to" class="form-control" size="8" multiple="multiple">
            <?php foreach ($elements['selected'] as $product): ?>
                <option value="<?= $product->get_id() ?>"><?= $product->get_id() . ' - ' . $product->get_name() ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="loader">
    <div class="loader-spinner"></div>
    <div class="background"></div>
    <h2 class="text">Loading...</h2>
</div>
