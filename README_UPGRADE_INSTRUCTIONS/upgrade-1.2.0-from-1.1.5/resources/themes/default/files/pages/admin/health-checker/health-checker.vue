<template>
    <main class="whitebox" id="settings-page">
        <button @click="healthStatus()" class="btn btn-warning mb-4" v-button-loading="busy">{{
            $t('admin.health_checker.action_button')}}
        </button>

        <div v-if="health && !busy">
            <div class="mb-4">
                <small>{{ $t('admin.last_checked') }}: {{health.date}}</small>
            </div>

            <div class="health">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>{{ $t('admin.health_checker.service') }}</th>
                        <th>{{ $t('admin.health_checker.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $t('admin.health_checker.bitcoin_node') }}</td>
                        <td><span :class="{'badge badge-pill' : true, 'badge-success': health.bitcoin, 'badge-secondary': !health.bitcoin}">{{ health.bitcoin ? $t('admin.online') : $t('admin.offline') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ $t('admin.health_checker.queue_workers') }}</td>
                        <td><span :class="{'badge badge-pill' : true, 'badge-success': health.queue, 'badge-secondary': !health.queue}">{{ health.queue ? $t('admin.online') : $t('admin.offline') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ $t('admin.health_checker.mail_system') }}</td>
                        <td><span :class="{'badge badge-pill' : true, 'badge-success': health.mail, 'badge-secondary': !health.mail}">{{ health.mail ? $t('admin.online') : $t('admin.offline') }}</span></td>
                    </tr>
                    <tr>
                        <td>{{ $t('admin.health_checker.websockets') }}</td>
                        <td><span :class="{'badge badge-pill' : true, 'badge-success': websocket, 'badge-secondary': !websocket}">{{ websocket ? $t('admin.online') : $t('admin.offline') }}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</template>

<script>
    import axios from 'axios'
    export default {
        data: () => ({
            health: false,
            busy: false,
            websocket: false,
        }),
        mounted() {
            this.healthStatus(1);
        },
        methods: {
            async websocketStatus() {
                this.websocket = window.Echo && window.Echo.connector.socket.connected;
            },
            async healthStatus(cached = 0) {
                try {
                    if(this.busy) return false;

                    this.busy = true;
                    let url = '/health-checker';
                    let response = await axios.get(url + '?cached=' + cached);
                    this.health = response.data;
                    this.websocketStatus();
                    this.busy = false;
                } catch (e) {
                    this.busy = false;
                }
            },
        },
    }
</script>
