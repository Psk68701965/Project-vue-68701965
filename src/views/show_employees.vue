<template>
    <div class="container mt-4">
      <!-- หัวข้อของตาราง -->
      <h2 class="mb-3">Show employees</h2>

      <!-- Search bar -->
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <input v-model="searchQuery" type="text" class="form-control w-50" placeholder="ค้นหาพนักงาน..." />
        <div>
          <button @click="editSelected" :disabled="selectedEmployees.length !== 1" class="btn btn-warning me-2">Edit Selected</button>
          <button @click="deleteSelected" :disabled="selectedEmployees.length === 0" class="btn btn-danger">Delete Selected</button>
        </div>
      </div>

      <!-- ตารางแสดงข้อมูลผู้ใช้ -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">รหัสพนักงาน</th>
            <th scope="col">ชื่อ-นามสกุล</th>
            <th scope="col">แผนก</th>
            <th scope="col">เงินเดือน</th>
            <th scope="col">สถาณะ</th>
            <th scope="col">วันท่ทำรายการ</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredEmployees" :key="item.emp_id">
            <td>{{ item.emp_id}} </td>
            <td>{{ item.full_name}} </td>
            <td>{{ item.department}} </td>
            <td>{{ item.salary}} </td>
            <td>
                <span v-if="item.active==1">ปกติ</span>
                <span v-if="item.active==0">ลาออก</span>
            </td>
            <td>{{ item.created_at}} </td>
            <td>
              <button @click="openEditModal(item)" class="btn btn-sm btn-warning me-2">Edit</button>
              <button @click="deleteEmployee(item.emp_id)" class="btn btn-sm btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Edit Modal -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitEdit">
                <div class="mb-3">
                  <label for="editEmpId" class="form-label">Employee ID</label>
                  <input id="editEmpId" v-model="editForm.emp_id" type="text" class="form-control" readonly />
                </div>
                <div class="mb-3">
                  <label for="editFullName" class="form-label">Full Name</label>
                  <input id="editFullName" v-model="editForm.full_name" type="text" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editDepartment" class="form-label">Department</label>
                  <input id="editDepartment" v-model="editForm.department" type="text" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editSalary" class="form-label">Salary</label>
                  <input id="editSalary" v-model.number="editForm.salary" type="number" step="0.01" class="form-control" required />
                </div>
                <div class="form-check mb-3">
                  <input id="editActive" type="checkbox" class="form-check-input" v-model="editForm.active" />
                  <label for="editActive" class="form-check-label">Active</label>
                </div>
                <button type="submit" class="btn btn-primary" :disabled="loading">Update</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Modal } from 'bootstrap'

const employees_id = ref([])
const loading = ref(true)
const error = ref(null)
const searchQuery = ref('')
const editForm = ref({ emp_id: '', full_name: '', department: '', salary: 0, active: true })

const filteredEmployees = computed(() => {
  if (!searchQuery.value) return employees_id.value
  return employees_id.value.filter(employee =>
    employee.full_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    employee.department.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    employee.emp_id.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

onMounted(async () => {
  await loadEmployees()
})

async function loadEmployees() {
  try {
    const res = await fetch('http://localhost/project-vue-68701965/php_api/show_employee.php')
    employees_id.value = await res.json()
  } catch (err) {
    error.value = 'โหลดข้อมูลไม่สำเร็จ'
  } finally {
    loading.value = false
  }
}

function openEditModal(employee) {
  editForm.value = { ...employee, active: employee.active == 1 }
  const modal = new Modal(document.getElementById('editModal'))
  modal.show()
}

async function submitEdit() {
  loading.value = true
  const data = { ...editForm.value, active: editForm.value.active ? 1 : 0 }

  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/edit_employee.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    const result = await response.json()
    if (result.status === 'success') {
      await loadEmployees()
      Modal.getInstance(document.getElementById('editModal')).hide()
    } else {
      alert(result.message)
    }
  } catch (err) {
    alert('Error: ' + err.message)
  } finally {
    loading.value = false
  }
}

async function deleteEmployee(emp_id) {
  if (!confirm('Are you sure you want to delete this employee?')) return
  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/delete_employee.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ emp_id })
    })
    const result = await response.json()
    if (result.status === 'success') {
      await loadEmployees()
    } else {
      alert(result.message)
    }
  } catch (err) {
    alert('Error: ' + err.message)
  }
}
</script>
