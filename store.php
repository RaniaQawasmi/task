<?php
session_start();
require_once "app/Controllers/StoreController.php";
require_once "app/Controllers/StudentController.php"; // عشان السايد بار

// التحقق من تسجيل الدخول
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// معلومات الطالب للسايد بار
$studentController = new StudentController();
$student = $studentController->getById($_SESSION['student_id']);

$controller = new StoreController();

// التعامل مع إضافة أو تعديل منتج
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_item'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $id = $_POST['item_id'] ?? null; // موجود لو تعديل

    if($name && $price && $quantity){
        if($id){ 
            $controller->updateItem($id, $name, $price, $quantity);
            $_SESSION['success'] = "Item updated successfully";
        } else {
            $controller->addItem($name, $price, $quantity);
            $_SESSION['success'] = "Item added successfully";
        }
        header("Location: store.php");
        exit;
    } else {
        $_SESSION['error'] = "All fields are required";
        header("Location: store.php");
        exit;
    }
}

// التعامل مع حذف منتج
if(isset($_GET['delete'])){
    $controller->deleteItem($_GET['delete']);
    $_SESSION['success'] = "Item deleted successfully";
    header("Location: store.php");
    exit;
}

// جلب كل المنتجات
$items = $controller->getAllItems();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$active = 'store'; // عشان الزر يكون Active
include 'sidebar.php';
?>

<div class="content">
  <div class="container">
      <h2>Store Management</h2>

      <?php if($success) echo "<p class='success'>$success</p>"; ?>
      <?php if($error) echo "<p class='error'>$error</p>"; ?>

      <!-- زر فتح الفورم -->
      <button class="add-btn" onclick="openForm()">Add Item</button>

      <!-- جدول المنتجات -->
      <table>
          <tr>
              <th>Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Actions</th>
          </tr>
          <?php foreach($items as $item): ?>
          <tr>
              <td><?= $item['name'] ?></td>
              <td><?= $item['price'] ?></td>
              <td><?= $item['quantity'] ?></td>
              <td>
                  <button class="edit-btn" onclick="openForm(<?= $item['id'] ?>, '<?= $item['name'] ?>', <?= $item['price'] ?>, <?= $item['quantity'] ?>)">Edit</button>
                  <a href="store.php?delete=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">
                      <button class="delete-btn">Delete</button>
                  </a>
              </td>
          </tr>
          <?php endforeach; ?>
      </table>

      <!-- Popup Form -->
      <div id="popupForm">
          <div class="form-container">
              <h3 id="formTitle">Add New Item</h3>
              <form method="POST">
                  <input type="hidden" name="item_id" id="item_id">
                  <input type="text" name="name" id="name" placeholder="Item Name" required>
                  <input type="number" name="price" id="price" placeholder="Price" step="0.01" required>
                  <input type="number" name="quantity" id="quantity" placeholder="Quantity" required>
                  <button type="submit" name="save_item" class="add-btn" id="saveBtn">Save</button>
                  <button type="button" class="close-btn" onclick="closeForm()">Cancel</button>
              </form>
          </div>
      </div>

  </div>
</div>

<script>
function openForm(id = null, name = '', price = '', quantity = '') {
    document.getElementById('popupForm').style.display = 'flex';
    if(id){
        document.getElementById('formTitle').innerText = 'Edit Item';
        document.getElementById('item_id').value = id;
        document.getElementById('name').value = name;
        document.getElementById('price').value = price;
        document.getElementById('quantity').value = quantity;
    } else {
        document.getElementById('formTitle').innerText = 'Add New Item';
        document.getElementById('item_id').value = '';
        document.getElementById('name').value = '';
        document.getElementById('price').value = '';
        document.getElementById('quantity').value = '';
    }
}
function closeForm(){
    document.getElementById('popupForm').style.display = 'none';
}
</script>

<link rel="stylesheet" href="store.css">