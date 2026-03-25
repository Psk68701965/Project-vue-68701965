<template>
    <div class="container mt-4">
      <!-- หัวข้อของตาราง -->
      <h2 class="mb-3">Show employees</h2>

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

    
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in employees_id" :key="item.employees_id">
            <td>{{ item.emp_id}} </td>
            <td>{{ item.full_name}} </td>
            <td>{{ item.department}} </td>
            <td>{{ item.salary}} </td>
            <td>
                <span v-if="item.active==1">ปกติ</span>
                <span v-if="item.active==0">ลาออก</span>
            </td>
            <td>{{ item.created_at}} </td>
            
            
         
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const employees_id = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
  try {
    const res = await fetch('http://localhost/project-vue-68701965/php_api/show_employee.php')
    employees_id.value = await res.json()
  } catch (err) {
    error.value = 'โหลดข้อมูลไม่สำเร็จ'
  } finally {
    loading.value = false
  }
})
</script>
