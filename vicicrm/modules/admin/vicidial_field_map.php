<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../lib/auth/Auth.php';
require_once __DIR__ . '/../../lib/vicidial/VicidialFieldMap.php';
Auth::requireAdmin();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['field'])) {
    foreach ($_POST['field'] as $id=>$data) {
        $stmt = db()->prepare("UPDATE crm_vicidial_field_map SET label=?,field_group=?,show_in_popup=?,show_in_view=?,show_in_edit=?,visible_admin=?,visible_supervisor=?,visible_agent=?,editable=?,sort_order=? WHERE id=?");
        $stmt->execute([
            $data['label'],
            $data['field_group'],
            isset($data['show_in_popup'])?1:0,
            isset($data['show_in_view'])?1:0,
            isset($data['show_in_edit'])?1:0,
            isset($data['visible_admin'])?1:0,
            isset($data['visible_supervisor'])?1:0,
            isset($data['visible_agent'])?1:0,
            isset($data['editable'])?1:0,
            intval($data['sort_order'] ?? 0),
            $id
        ]);
    }
    header('Location: vicidial_field_map.php?saved=1');
    exit;
}

$fields = VicidialFieldMap::all();

include __DIR__ . '/../../header.php';
include __DIR__ . '/../../sidebar.php';
?>
<div class="content" style="margin-left:220px;padding:20px;">
<h2>Vicidial Field Mapping</h2>
<?php if(isset($_GET['imported'])): ?><div style="background:#0f0;color:#000;padding:8px;">Imported.</div><?php endif; ?>
<?php if(isset($_GET['saved'])): ?><div style="background:#0f0;color:#000;padding:8px;">Saved.</div><?php endif; ?>

<p><a href="vicidial_field_import.php" class="btn">Import Fields from vicidial_list</a></p>

<form method="post">
<table style="width:100%;border-collapse:collapse;">
<tr style="background:#222;color:#fff;">
<th>Field</th><th>Label</th><th>Group</th><th>Popup</th><th>View</th><th>Edit</th><th>Admin</th><th>Sup</th><th>Agent</th><th>Edit?</th><th>Sort</th>
</tr>
<?php foreach($fields as $f): ?>
<tr style="border-top:1px solid #333;">
<td><?= htmlspecialchars($f['vicidial_field']) ?></td>
<td><input type="text" name="field[<?= $f['id'] ?>][label]" value="<?= htmlspecialchars($f['label']) ?>"></td>
<td><input type="text" name="field[<?= $f['id'] ?>][field_group]" value="<?= htmlspecialchars($f['field_group']) ?>"></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][show_in_popup]" <?= $f['show_in_popup']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][show_in_view]" <?= $f['show_in_view']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][show_in_edit]" <?= $f['show_in_edit']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][visible_admin]" <?= $f['visible_admin']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][visible_supervisor]" <?= $f['visible_supervisor']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][visible_agent]" <?= $f['visible_agent']?'checked':'' ?>></td>
<td><input type="checkbox" name="field[<?= $f['id'] ?>][editable]" <?= $f['editable']?'checked':'' ?>></td>
<td><input type="number" name="field[<?= $f['id'] ?>][sort_order]" value="<?= intval($f['sort_order']) ?>" style="width:70px"></td>
</tr>
<?php endforeach; ?>
</table>
<br><button type="submit">Save Mapping</button>
</form>
</div>
<?php include __DIR__ . '/../../footer.php'; ?>