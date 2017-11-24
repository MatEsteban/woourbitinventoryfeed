<select name="<?= $name ?>">
    <?php foreach ($elements as $element): ?>
        <option value="<?= $element['value'] ?>" <?= isset($element['param']) ? $element['param'] : '' ?>><?= $element['text'] ?></option>
    <?php endforeach; ?>
</select>