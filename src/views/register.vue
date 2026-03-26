<template>
  <div class="container mt-5">
    <h2 class="mb-4">Register</h2>

    <div v-if="alert.message" class="alert" :class="alertClass" role="alert">
      {{ alert.message }}
    </div>

    <form @submit.prevent="submitRegister" class="w-100" style="max-width: 520px;">
      <div class="mb-3">
        <label for="firstName" class="form-label">First Name</label>
        <input id="firstName" v-model="form.firstName" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="lastName" class="form-label">Last Name</label>
        <input id="lastName" v-model="form.lastName" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input id="phone" v-model="form.phone" type="tel" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" v-model="form.username" type="text" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" v-model="form.password" type="password" class="form-control" required minlength="6" />
      </div>

      <button :disabled="loading" type="submit" class="btn btn-primary">
        {{ loading ? 'Registering...' : 'Register' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'

const form = reactive({ firstName: '', lastName: '', phone: '', username: '', password: '' })
const loading = ref(false)
const alert = reactive({ message: '', type: '' })

const alertClass = computed(() => {
  return alert.type === 'success' ? 'alert-success' : 'alert-danger'
})

async function submitRegister() {
  alert.message = ''
  alert.type = ''
  loading.value = true

  try {
    const response = await fetch('http://localhost/project-vue-68701965/php_api/add_customer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form)
    })

    const data = await response.json()

    if (data.status === 'success') {
      alert.message = 'Register successful! You can now go to customer list or login.'
      alert.type = 'success'
      form.firstName = ''
      form.lastName = ''
      form.phone = ''
      form.username = ''
      form.password = ''
    } else {
      alert.message = data.message || 'Register failed. Please try again.'
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
