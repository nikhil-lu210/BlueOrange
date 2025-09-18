import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'

// Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'

// Custom CSS
import './assets/css/app.css'

const app = createApp(App)

app.use(createPinia())

app.mount('#app')
