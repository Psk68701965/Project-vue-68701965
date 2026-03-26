<template>
    <div class="container mt-4">
      <!-- หัวข้อของตาราง -->
      <h2 class="mb-3">show_customer</h2>

      <!-- Search bar -->
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <input v-model="searchQuery" type="text" class="form-control w-50" placeholder="ค้นหาลูกค้า..." />
        <div>
          <button @click="editSelected" :disabled="selectedCustomers.length !== 1" class="btn btn-warning me-2">Edit Selected</button>
          <button @click="deleteSelected" :disabled="selectedCustomers.length === 0" class="btn btn-danger">Delete Selected</button>
        </div>
      </div>

      <!-- ตารางแสดงข้อมูลผู้ใช้ -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col"><input type="checkbox" @change="selectAll" v-model="selectAllChecked" /></th>
            <th scope="col">ID</th>
            <th scope="col">ชื่อ</th>
            <th scope="col">สกุล</th>
            <th scope="col">เบอร์โทร</th>
            <th scope="col">username</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredCustomers" :key="item.customer_id">
            <td><input type="checkbox" v-model="selectedCustomers" :value="item.customer_id" /></td>
            <td>{{ item.customer_id}} </td>
            <td>{{ item.firstName}} </td>
            <td>{{ item.lastName}} </td>
            <td>{{ item.phone}} </td>
            <td>{{ item.username}} </td>
          </tr>
        </tbody>
      </table>

      <!-- Edit Modal -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitEdit">
                <div class="mb-3">
                  <label for="editFirstName" class="form-label">First Name</label>
                  <input id="editFirstName" v-model="editForm.firstName" type="text" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editLastName" class="form-label">Last Name</label>
                  <input id="editLastName" v-model="editForm.lastName" type="text" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editPhone" class="form-label">Phone</label>
                  <input id="editPhone" v-model="editForm.phone" type="tel" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editUsername" class="form-label">Username</label>
                  <input id="editUsername" v-model="editForm.username" type="text" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label for="editPassword" class="form-label">Password (leave blank to keep)</label>
                  <input id="editPassword" v-model="editForm.password" type="password" class="form-control" />
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

const customers = ref([])
const loading = ref(true)
const error = ref(null)
const searchQuery = ref('')
const editForm = ref({ customer_id: '', firstName: '', lastName: '', phone: '', username: '', password: '' })
const selectedCustomers = ref([])
const selectAllChecked = ref(false)

const filteredCustomers = computed(() => {
  if (!searchQuery.value) return customers.value
  return customers.value.filter(customer =>
    customer.firstName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    customer.lastName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    customer.username.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

onMounted(async () => {
  await loadCustomers()
})

async function loadCustomers() {
  try {
    const res = await fetch('http://localhost/project-vue-68701965/php_api/show_customer.php')
    customers.value = await res.json()
  } catch (err) {
    error.value = 'โหลดข้อมูลไม่สำเร็จ'
  } finally {
    loading.value = false
  }
}

function openEditModal(customer) {
  editForm.value = { ...customer, password: '' }
  const modal = new Modal(document.getElementById('editModal'))
  modal.show()
}

async function submitEdit() {
  loading.value = true
  const data = { ...editForm.value }
  if (!data.password) delete data.password // Don't update password if empty

  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/edit_customer.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    const result = await response.json()
    if (result.status === 'success') {
      await loadCustomers()
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

async function deleteCustomer(customer_id) {
  if (!confirm('Are you sure you want to delete this customer?')) return
  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/delete_customer.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ customer_id })
    })
    const result = await response.json()
    if (result.status === 'success') {
      await loadCustomers()
    } else {
      alert(result.message)
    }
  } catch (err) {
    alert('Error: ' + err.message)
  }
}

function selectAll() {
  if (selectAllChecked.value) {
    selectedCustomers.value = filteredCustomers.value.map(c => c.customer_id)
  } else {
    selectedCustomers.value = []
  }
}

function editSelected() {
  if (selectedCustomers.value.length === 1) {
    const customer = customers.value.find(c => c.customer_id === selectedCustomers.value[0])
    openEditModal(customer)
  }
}

async function deleteSelected() {
  if (selectedCustomers.value.length === 0) return
  if (!confirm(`Are you sure you want to delete ${selectedCustomers.value.length} customer(s)?`)) return
  for (const id of selectedCustomers.value) {
    try {
      const response = await fetch('http://localhost/project-vue-68701965/php_api/delete_customer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ customer_id: id })
      })
      const result = await response.json()
      if (result.status !== 'success') {
        alert(`Error deleting customer ${id}: ${result.message}`)
      }
    } catch (err) {
      alert(`Error deleting customer ${id}: ${err.message}`)
    }
  }
  selectedCustomers.value = []
  await loadCustomers()
}
</script>
