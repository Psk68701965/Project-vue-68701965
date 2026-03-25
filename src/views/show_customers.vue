<template>
    <div class="container mt-4">
      <!-- หัวข้อของตาราง -->
      <h2 class="mb-3">show_customer</h2>

      <!-- ตารางแสดงข้อมูลผู้ใช้ -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">ชื่อ</th>
            <th scope="col">สกุล</th>
            <th scope="col">เบอร์โทร</th>
            <th scope="col">username</th>
    
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in customers" :key="item.customers_id">
            <td>{{ item.customer_id}} </td>
            <td>{{ item.firstName}} </td>
            <td>{{ item.lastName}} </td>
            <td>{{ item.phone}} </td>
            <td>{{ item.username}} </td>
         
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const customers = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const res = await fetch('http://localhost/project-vue-68701965/php_api/show_customer.php')
    customers.value = await res.json()
  } catch (err) {
    error.value = 'โหลดข้อมูลไม่สำเร็จ'
  } finally {
    loading.value = false
  }
})
</script>
