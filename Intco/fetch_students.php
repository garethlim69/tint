<?php
require '../Config/db.php';

if (isset($_GET['teacher_id'])) {
  $teacherId = $_GET['teacher_id'];

  $studentsQuery = "SELECT 
            s.student_id, 
            s.name AS student_name, 
            s.email AS student_email, 
            io.is_email, 
            isup.name AS is_name, 
            isup.company_name AS company,
            CASE 
                WHEN io.as_email = :teacherId THEN 1 
                ELSE 0 
            END AS is_assigned
        FROM student AS s
        LEFT JOIN internshipoffer AS io 
            ON io.student_id = s.student_id  
        LEFT JOIN industrysupervisor AS isup 
            ON io.is_email = isup.email
        WHERE io.as_email = :teacherId OR io.as_email IS NULL 
        ORDER BY is_assigned DESC, s.name ASC;
    ";

  $stmt = $pdo->prepare($studentsQuery);
  $stmt->execute(['teacherId' => $teacherId]);
  $studentsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($studentsResult as $result):
    $isAssigned = $result['is_assigned'] == 1;
?>
    <tr>
      <td><input type="checkbox" name="students[]" value="<?= $result['student_id'] ?>" <?= $isAssigned ? 'checked' : '' ?> class="select-student"></td>
      <td><?= htmlspecialchars($result['student_name']) ?></td>
      <td><?= htmlspecialchars($result['student_id']) ?></td>
      <td><a href="mailto:<?= htmlspecialchars($result['student_email']) ?>"><?= htmlspecialchars($result['student_email']) ?></a></td>
      <td><?= htmlspecialchars($result['company'] ?? 'N/A') ?></td>
      <td><?= htmlspecialchars($result['is_name'] ?? 'N/A') ?></td>
    </tr>
<?php
  endforeach;
}
?>