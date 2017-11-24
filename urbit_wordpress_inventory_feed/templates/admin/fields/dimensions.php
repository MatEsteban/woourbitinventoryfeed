<?php foreach ($dimensions as $dimension): ?>

    <fieldset>
        <legend><?= $dimension['title'] ?></legend>
        <input type="number" step="0.01" name="<?= $name . '[' . $dimension['id'] . ']' . '[from]' ?>" value="<?= $dimension['values']['from'] ?>" placeholder="From"> -
        <input type="number" step="0.01" name="<?= $name . '[' . $dimension['id'] . ']' . '[to]' ?>" value="<?= $dimension['values']['to'] ?>" placeholder="To">
    </fieldset>

    <br>

<?php endforeach; ?>