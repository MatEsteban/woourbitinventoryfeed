<select multiple="multiple" size="<?= $size ?>" name="<?= $name ?>">
    <?php foreach ($elements as $element): ?>
        <option value="<?= $element['value'] ?>" <?= $element['param'] ?>><?= $element['text'] ?></option>
    <?php endforeach; ?>
</select>