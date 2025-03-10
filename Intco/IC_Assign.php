<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign & Add Student</title>
  <link rel="stylesheet" href="IC_Assign.css">
  <link rel="stylesheet" href="IntCoHeader.css">
</head>
<body>

  <!-- 顶部导航栏 -->
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

  <!-- 主内容区 (Assign 页面) -->
  <div class="container">
    <!-- 标题 + 搜索框 + Auto-assign + Add Student -->
    <div class="title-row">
      <h2>Assign</h2>
      <div class="top-actions">
        <div class="search-box">
          <input type="text" placeholder="Search...">
          <button class="auto-assign-btn">Auto-assign</button>
        </div>
      </div>
    </div>

    <!-- Academic Supervisor 表格 -->
    <table class="outer-table">
      <thead>
        <tr>
          <th>Academic Supervisor Name</th>
          <th>Email</th>
          <th>Faculty</th>
          <th>No. of Students</th>
        </tr>
      </thead>
      <tbody>
        <!-- Esmee Hickman -->
        <tr class="main-row" data-target="sub-esmee">
          <td class="toggle-subrow">+ Esmee Hickman</td>
          <td><a href="mailto:123456@sd.taylors.edu.my" class="email-link">123456@sd.taylors.edu.my</a></td>
          <td>Computing</td>
          <td>1/5</td>
        </tr>
        <tr id="sub-esmee" class="sub-row">
          <td colspan="4">
            <table class="inner-table">
              <thead>
                <tr>
                  <th>Student Name</th>
                  <th>Student ID</th>
                  <th>Email</th>
                  <th>Company Name</th>
                  <th>Industry Supervisor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1. Charlotte Colon</td>
                  <td>0185728</td>
                  <td><a href="mailto:2185728@sd.taylors.edu.my" class="email-link">2185728@sd.taylors.edu.my</a></td>
                  <td>Apple</td>
                  <td>Tim Mitchell</td>
                </tr>
                 <!-- showmore_withcircle_icon -->
                <tr>
                  <td class="showmore_withcircle_icon_container" colspan="5">
                            <!-- Add Student  -->
                    <img class="showmore_withcircle_icon openModalBtn" src="picture/showmore_withcircle.png"
                    >
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <!-- Darcey Mennell -->
        <tr class="main-row" data-target="sub-darcey">
          <td class="toggle-subrow">+ Darcey Mennell</td>
          <td><a href="mailto:darmcn@taylors.edu.my" class="email-link">darmcn@taylors.edu.my</a></td>
          <td>Computing</td>
          <td>5/5</td>
        </tr>
        <tr id="sub-darcey" class="sub-row">
          <td colspan="4">
            <table class="inner-table">
              <thead>
                <tr>
                  <th>Student Name</th>
                  <th>Student ID</th>
                  <th>Email</th>
                  <th>Company Name</th>
                  <th>Industry Supervisor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1. David Powell</td>
                  <td>0157528</td>
                  <td><a href="mailto:123456@sd.taylors.edu.my" class="email-link">123456@sd.taylors.edu.my</a></td>
                  <td>Shell</td>
                  <td>Maxim Dillon</td>
                </tr>
                <tr>
                  <td>2. Daisy Oneal</td>
                  <td>0173339</td>
                  <td><a href="mailto:123456@sd.taylors.edu.my" class="email-link">123456@sd.taylors.edu.my</a></td>
                  <td>Shell</td>
                  <td>Maxim Dillon</td>
                </tr>
                <tr>
                  <td>3. Kelsie Stafford</td>
                  <td>0198765</td>
                  <td><a href="mailto:734363@sd.taylors.edu.my" class="email-link">734363@sd.taylors.edu.my</a></td>
                  <td>Shell</td>
                  <td>Maxim Dillon</td>
                </tr>
                <tr>
                  <td>4. Ashton Crane</td>
                  <td>0123467</td>
                  <td><a href="mailto:734363@sd.taylors.edu.my" class="email-link">734363@sd.taylors.edu.my</a></td>
                  <td>Shell</td>
                  <td>Maxim Dillon</td>
                </tr>
                   <!-- showmore_withcircle_icon -->
                   <tr>
                    <td class="showmore_withcircle_icon_container" colspan="5">
                              <!-- Add Student  -->
                      <img class="showmore_withcircle_icon openModalBtn" src="picture/showmore_withcircle.png"
                      >
                    </td>
                  </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <!-- Ellis Dalton -->
        <tr>
          <td>+ Ellis Dalton</td>
          <td><a href="mailto:elldal@taylors.edu.my" class="email-link">elldal@taylors.edu.my</a></td>
          <td>Computing</td>
          <td>0/5</td>
        </tr>

        <!-- Sadie Buckley -->
        <tr>
          <td>+ Sadie Buckley</td>
          <td><a href="mailto:sadbuck@taylors.edu.my" class="email-link">sadbuck@taylors.edu.my</a></td>
          <td>Computing</td>
          <td>0/5</td>
        </tr>
      </tbody>
    </table>

    <!-- 底部按钮行 -->
    <div class="button-row">
      <button class="discard-btn">Discard</button>
      <button class="save-btn">Save</button>
    </div>
  </div>

  <!-- ===================== Add Student 模态 ===================== -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Add Student</h2>
      </div>
      <div class="modal-body">
        <!-- 搜索行 -->
        <div class="search-row">
          <div class="left-label">
            <label>Student Name:</label>
          </div>
          <input  type="text" placeholder="Search..." id="searchInput">
        </div>
        <!-- 学生列表表格 -->
        <table class="student-table">
          <thead>
            <tr>
              <th></th> <!-- 放复选框 -->
              <th>Student Name</th>
              <th>Student ID</th>
              <th>Email</th>
              <th>Company Name</th>
              <th>Industry Supervisor Name</th>
            </tr>
          </thead>
          <tbody>
            <!-- 示例数据 -->
            <tr>
              <td><input type="checkbox" class="select-student"></td>
              <td>Charlotte Colon</td>
              <td>0347528</td>
              <td>0347528@sd.taylors.edu.my</td>
              <td>Petronas</td>
              <td>Janice Mendoza</td>
            </tr>
            <tr>
              <td><input type="checkbox" class="select-student"></td>
              <td>Joyce Bernard</td>
              <td>0278246</td>
              <td>0278246@sd.taylors.edu.my</td>
              <td>Petronas</td>
              <td>Janice Mendoza</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button class="cancel-btn" id="cancelBtn">Cancel</button>
        <button class="change-btn" id="changeBtn">change Selected</button>
      </div>
    </div>
  </div>

  <script>
    // =========== 折叠/展开子行 =============
    document.querySelectorAll('.main-row').forEach(row => {
      const toggleCell = row.querySelector('.toggle-subrow');
      if (!toggleCell) return;

      const targetId = row.getAttribute('data-target');
      const subRow = document.getElementById(targetId);
      if (!subRow) return;

      // 初始隐藏子行
      subRow.style.display = 'none';

      // 切换显示/隐藏
      toggleCell.addEventListener('click', () => {
        if (subRow.style.display === 'none') {
          subRow.style.display = 'table-row';
        } else {
          subRow.style.display = 'none';
        }
      });
    });

    // =========== 打开/关闭 "Add Student" 模态 ============
    const openModalBtn = document.getElementById('openModalBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const cancelBtn = document.getElementById('cancelBtn');
    const addBtn = document.getElementById('addBtn');

    document.querySelectorAll('.openModalBtn').forEach(button =>{
      button.addEventListener('click', () => {
      modalOverlay.style.display = 'flex';
    });
  });

    cancelBtn.addEventListener('click', () => {
      modalOverlay.style.display = 'none';
    });

    // 点击 "Add Selected" 按钮
    addBtn.addEventListener('click', () => {
      const selectedStudents = [];
      document.querySelectorAll('.select-student:checked').forEach(chk => {
        const row = chk.closest('tr');
        const studentName = row.cells[1].innerText;
        selectedStudents.push(studentName);
      });
      if (selectedStudents.length === 0) {
        alert('No student selected!');
      } else {
        alert('Selected:\n' + selectedStudents.join('\n'));
        // 在此处可执行后端逻辑或更新表格
      }
      // 关闭模态
      modalOverlay.style.display = 'none';
    });
  </script>
</body>
</html>