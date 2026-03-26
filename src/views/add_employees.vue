<template>
  <div class="container mt-5" style="max-width: 700px;">
    <h2 class="mb-4">Add Employee</h2>

    <div v-if="alert.message" class="alert" :class="alertClass" role="alert">
      {{ alert.message }}
    </div>

    <form @submit.prevent="submitEmployee">
      <div class="mb-3">
        <label for="emp_id" class="form-label">Employee ID</label>
        <input id="emp_id" v-model="form.emp_id" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="full_name" class="form-label">ชื่อ-นามสกุล</label>
        <input id="full_name" v-model="form.full_name" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="department" class="form-label">แผนก</label>
        <select id="department" v-model="form.department" class="form-control" required>
          <option value="">เลือกแผนก</option>
          <option value="IT">IT</option>
          <option value="บัญชี">บัญชี</option>
          <option value="การตลาด">การตลาด</option>
          <option value="ทรัพยากรบุคคล">ทรัพยากรบุคคล</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="salary" class="form-label">เงินเดือน</label>
        <input id="salary" type="number" step="0.01" v-model.number="form.salary" class="form-control" required />
      </div>

      <div class="form-check mb-4">
        <input id="active" type="checkbox" class="form-check-input" v-model="form.active" />
        <label for="active" class="form-check-label">Active</label>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Saving...' : 'Save Employee' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'

const form = reactive({ emp_id: '', full_name: '', department: '', salary: 0, active: true })
const loading = ref(false)
const alert = reactive({ message: '', type: '' })

const alertClass = computed(() => {
  return alert.type === 'success' ? 'alert-success' : 'alert-danger'
})

async function submitEmployee() {
  alert.message = ''
  alert.type = ''
  loading.value = true

  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/add_employee.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        emp_id: form.emp_id,
        full_name: form.full_name,
        department: form.department,
        salary: form.salary,
        active: form.active ? 1 : 0
      })
    })

    const data = await response.json()

    if (data.status === 'success') {
      alert.message = data.message || 'เพิ่มพนักงานสำเร็จ'
      alert.type = 'success'
      form.emp_id = ''
      form.full_name = ''
      form.department = ''
      form.salary = 0
      form.active = true
    } else {
      alert.message = data.message || 'เกิดข้อผิดพลาดในการบันทึก'
      alert.type = 'error'
    }
  } catch (err) {
    alert.message = 'Network error: ' + err.message
    alert.type = 'error'
  } finally {
    loading.value = false
  }
}
</script>