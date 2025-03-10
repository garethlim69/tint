<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage - Tasks</title>
  <link rel="stylesheet" href="IntCoHeader.css">  
  <link rel="stylesheet" href="IntCoTasks.css">  
  
</head>
<body>

   <!-- navigationbar -->
   <div class = "header"
   >
     <div class = "tint_logo">
       <img class="logo" 
       src="picture/logo.png" >
       <p class ="tint_title">
           t-int
       </p>
     </div>
    
     <div class = "navigationbar">
       <div class="navigationbar_link">
         <a href="IntCoHome.php">Home</a>
         
       </div>
 
       </div>
     <div class="profile">
       <img class ="profile_icon"
       src="picture/profile.png">
       <div class="profile_dropdown">
           <a href="ICProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
           <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
          </div> 
     </div>

     
   </div>

  <!-- 主体内容 -->
  <div class="container">
    <h2>Manage - Tasks</h2>

    <!-- 右上角 Student 按钮 -->
    <div class="role-selector">
      <div class="dropdown">Student ▼</div>
    </div>

    <!-- 任务表格 -->
    <table class="task-table">
      <thead>
        <tr>
          <th>Task Name</th>
          <th>Due Date (dd/mm/yyyy)</th>
          <th></th> <!-- 第三列留空放操作图标 -->
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Complete Profile</td>
          <td>16/05/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
        <tr>
          <td>Submit Appraisal 1</td>
          <td>27/05/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
        <tr>
          <td>Submit Weekly Logsheet</td>
          <td>05/07/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
        <tr>
          <td>Submit Feedback Form</td>
          <td>24/08/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
        <tr>
          <td>Submit Self-reflection Form</td>
          <td>16/09/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
        <tr>
          <td>Submit Appraisal 2</td>
          <td>29/09/2024</td>
          <td class="action-icons">
            <img src="picture/edit_icon.png" alt="Edit" class="edit-btn">
            <img src="picture/delete_icon.png" alt="Delete">
          </td>
        </tr>
      </tbody>
    </table>

    <!-- 添加任务按钮 -->
    <div class="add-button">
      <button id="addTaskBtn">+</button>
    </div>
  </div>

  <!-- “Add Task” 弹窗 -->
  <div class="modal-overlay" id="addModal">
    <div class="modal">
      <h3>Add Task</h3>
      <label for="taskName">Task Name:</label>
      <input type="text" id="taskName" value="Task 7">
      
      <label for="dueDate">Due Date:</label>
      <!-- 可用 <input type="date"> 或你自己的日期选择器 -->
      <input type="date" id="dueDate">
      
      <div class="modal-buttons">
        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
        <button class="save-btn" onclick="saveTask()">Save</button>
      </div>
    </div>
  </div>

  <!-- “Edit Task” 弹窗 -->
  <div class="modal-overlay" id="editModal">
    <div class="modal">
      <h3>Edit Task</h3>
      <label for="taskName">Task Name:</label>
      <input type="text" id="taskName" value="Task 7">
      
      <label for="dueDate">Due Date:</label>
      <!-- 可用 <input type="date"> 或你自己的日期选择 UI -->
      <input type="date" id="dueDate">
      
      <div class="modal-buttons">
        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
        <button class="save-btn" onclick="saveTask()">Save</button>
      </div>
    </div>
  </div>

  <script>

    // 打开/关闭弹窗
    const modalOverlay = document.getElementById('editModal');
    const editButtons = document.querySelectorAll('.edit-btn');

    // 为每个编辑图标添加点击事件
    editButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        // 这里可以根据行的数据更新弹窗的值
        // 例如: document.getElementById("taskName").value = "Complete Profile";
        // 这里先用默认值 "Task 7"
        modalOverlay.style.display = 'flex';
      });
    });

    

    function saveTask() {
      // 这里可以做保存逻辑，比如获取输入值后保存到后端
      const name = document.getElementById('taskName').value;
      const date = document.getElementById('dueDate').value;
      alert("Task Updated:\n" + name + "\nDue: " + date);
      closeModal();
    }

    const addModal = document.getElementById('addModal');
    const addTaskBtn = document.getElementById('addTaskBtn');

    // 打开“Add Task”弹窗
    addTaskBtn.addEventListener('click', () => {
      // 可以在这里设置默认值，或让用户填写
      document.getElementById('taskName').value = 'Task 7';
      document.getElementById('dueDate').value = '';
      addModal.style.display = 'flex';
    });

    // 关闭弹窗
    function closeModal() {
      modalOverlay.style.display = 'none';
      addModal.style.display = 'none';
    }

    // 点击“Save”时触发的函数（可替换为实际保存逻辑）
    function saveTask() {
      const name = document.getElementById('taskName').value;
      const date = document.getElementById('dueDate').value;
      alert("New Task Added:\n" + name + "\nDue: " + date);
      closeModal();
    }
  </script>

</body>
</html>