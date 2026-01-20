// Auth Tab Switcher
function switchAuthTab(tab) {
    const loginTab = document.getElementById('tab-login');
    const registerTab = document.getElementById('tab-register');
    const loginForm = document.getElementById('auth-login');
    const registerForm = document.getElementById('auth-register');
    
    if (tab === 'login') {
        loginTab.classList.add('border-b-2', 'border-primary', 'text-primary', 'bg-blue-50');
        loginTab.classList.remove('text-gray-600');
        registerTab.classList.remove('border-b-2', 'border-primary', 'text-primary', 'bg-blue-50');
        registerTab.classList.add('text-gray-600');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    } else {
        registerTab.classList.add('border-b-2', 'border-primary', 'text-primary', 'bg-blue-50');
        registerTab.classList.remove('text-gray-600');
        loginTab.classList.remove('border-b-2', 'border-primary', 'text-primary', 'bg-blue-50');
        loginTab.classList.add('text-gray-600');
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    }
}

// Login Handler
function handleLogin(event) {
    event.preventDefault();
    alert('เข้าสู่ระบบสำเร็จ! (Mockup - ไม่มีการตรวจสอบจริง)');
    // In real app, this would authenticate user
}

// Register Handler
function handleRegister(event) {
    event.preventDefault();
    alert('สมัครสมาชิกสำเร็จ! กรุณาตรวจสอบอีเมลเพื่อยืนยันบัญชี (Mockup - ไม่มีการตรวจสอบจริง)');
    // In real app, this would create user account
}
