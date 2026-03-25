<template>
  <div class="container mt-5" style="max-width: 580px;">
    <h2 class="mb-4">Register</h2>

    <form @submit.prevent="handleSubmit" novalidate>
      <div class="mb-3">
        <label class="form-label" for="firstName">ชื่อ</label>
        <input id="firstName" class="form-control" v-model="form.firstName" required />
      </div>

      <div class="mb-3">
        <label class="form-label" for="lastName">นามสกุล</label>
        <input id="lastName" class="form-control" v-model="form.lastName" required />
      </div>

      <div class="mb-3">
        <label class="form-label" for="phone">เบอร์โทร</label>
        <input id="phone" type="tel" class="form-control" v-model="form.phone" required />
      </div>

      <div class="mb-3">
        <label class="form-label" for="username">Username</label>
        <input id="username" class="form-control" v-model="form.username" required />
      </div>

      <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input id="password" type="password" class="form-control" v-model="form.password" required minlength="4" />
      </div>

      <div class="mb-3">
        <label class="form-label" for="confirmPassword">Confirm Password</label>
        <input id="confirmPassword" type="password" class="form-control" v-model="form.confirmPassword" required />
      </div>

      <button class="btn btn-primary" type="submit" :disabled="submitting">Register</button>
    </form>

    <div class="mt-3" v-if="message">{{ message }}</div>
    <div class="mt-3 text-danger" v-if="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const form = reactive({
  firstName: '',
  lastName: '',
  phone: '',
  username: '',
  password: '',
  confirmPassword: ''
})

const message = ref('')
const error = ref('')
const submitting = ref(false)

async function handleSubmit() {
  error.value = ''
  message.value = ''

  if (!form.firstName || !form.lastName || !form.phone || !form.username || !form.password) {
    error.value = 'กรุณากรอกข้อมูลให้ครบทุกช่อง'
    return
  }

  if (form.password !== form.confirmPassword) {
    error.value = 'รหัสผ่านไม่ตรงกัน'
    return
  }

  submitting.value = true

  try {
    const res = await fetch('http://localhost/project-vue-68701965/php_api/add_customer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        firstName: form.firstName,
        lastName: form.lastName,
        phone: form.phone,
        username: form.username,
        password: form.password
      })
    })

    const json = await res.json()

    if (!res.ok) {
      throw new Error(json.error || 'ไม่สามารถสมัครสมาชิกได้')
    }

    message.value = json.message || 'สมัครสมาชิกสำเร็จ'
    form.firstName = ''
    form.lastName = ''
    form.phone = ''
    form.username = ''
    form.password = ''
    form.confirmPassword = ''
  } catch (err) {
    error.value = err.message
  } finally {
    submitting.value = false
  }
}
</script>

