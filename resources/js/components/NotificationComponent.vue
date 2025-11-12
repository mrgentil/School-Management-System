<template>
    <div class="notification-container">
        <div v-if="notification.show" :class="['notification', notification.type]">
            {{ notification.message }}
            <button @click="closeNotification">×</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notification: {
                show: false,
                message: '',
                type: 'info'
            }
        }
    },
    created() {
        window.Echo.private(`user.${window.Laravel.user.id}`)
            .listen('.book.request.updated', (data) => {
                this.showNotification('Votre demande de livre a été mise à jour : ' + data.bookRequest.status, 'success');
            });
    },
    methods: {
        showNotification(message, type = 'info') {
            this.notification = {
                show: true,
                message,
                type
            };
            setTimeout(() => {
                this.closeNotification();
            }, 5000);
        },
        closeNotification() {
            this.notification.show = false;
        }
    }
}
</script>

<style>
.notification {
    padding: 15px;
    margin: 10px 0;
    border-radius: 4px;
    color: white;
    position: relative;
}

.notification.success {
    background-color: #4CAF50;
}

.notification.error {
    background-color: #f44336;
}

.notification button {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}
</style>