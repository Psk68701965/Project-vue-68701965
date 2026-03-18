<template>
    <!-- หัวข้อ -->
    <h2 class="text-center mb-4">💰 ราคาทองวันนี้</h2>

    <!-- ปุ่มกดเพื่อดึงข้อมูลใหม่ -->
    <div class="text-center mb-3">
      <!-- @click = event เมื่อกดปุ่ม -->
      <button class="btn btn-primary" @click="fetchGold">
        🔄 อัปเดต
      </button>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <table class="table table-bordered table-striped text-center">

      <!-- ส่วนหัวตาราง -->
      <thead class="table-dark">
        <tr>
          <th>ประเภททอง</th>
          <th>ราคารับซื้อ</th>
          <th>ราคาขายออก</th>
        </tr>
      </thead>

      <!-- ส่วนข้อมูล -->
      <tbody>
        <!-- v-for ใช้วนลูปข้อมูลใน golds -->
        <tr v-for="item in golds" :key="item.name">
          
          <!-- แสดงชื่อทอง -->
          <td>{{ item.name }}</td>

          <!-- แสดงราคาซื้อ (สีเขียว) -->
          <td class="text-success">
            {{ formatNumber(item.buy) }} บาท
          </td>

          <!-- แสดงราคาขาย (สีแดง) -->
          <td class="text-danger">
            {{ formatNumber(item.sell) }} บาท
          </td>

        </tr>
      </tbody>

    </table>
</template>
<script setup>
// import ฟังก์ชันจาก Vue 3
import { ref, onMounted } from 'vue'

// ตัวแปรเก็บข้อมูลราคาทอง (reactive)
const golds = ref([])

// ฟังก์ชันดึงข้อมูล API
const fetchGold = async () => {
  try {
    // เรียก API
    const res = await fetch('https://api.chnwt.dev/thai-gold-api/latest')

    // แปลง response เป็น JSON
    const data = await res.json()

    // เข้าถึงข้อมูล price
    const price = data.response.price

    // แปลงข้อมูลจาก object → array เพื่อใช้กับ v-for
    golds.value = [
      {
        name: "ทองรูปพรรณ",
        // API เป็น string มี comma → ต้องลบ comma แล้วแปลงเป็นตัวเลข
        buy: parseFloat(price.gold.buy.replace(/,/g, "")),
        sell: parseFloat(price.gold.sell.replace(/,/g, ""))
      },
      {
        name: "ทองคำแท่ง",
        buy: parseFloat(price.gold_bar.buy.replace(/,/g, "")),
        sell: parseFloat(price.gold_bar.sell.replace(/,/g, ""))
      }
    ]

  } catch (error) {
    // แสดง error ถ้าโหลดไม่สำเร็จ
    console.error("โหลดข้อมูลผิดพลาด:", error)
  }
}

// ฟังก์ชัน format ตัวเลขให้มี comma
const formatNumber = (num) => {
  return num.toLocaleString()
}

// เรียกใช้งานตอน component โหลดเสร็จ
onMounted(fetchGold)
</script>