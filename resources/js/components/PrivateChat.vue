<template>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-8">
                <ul class="chat">
                    <li class="left clearfix" v-for="message in messages">
                        <div class="chat-body clearfix">
                            <div class="header">
                                <strong class="primary-font">
                                    {{ message.user.name }}
                                </strong>
                            </div>
                            <p>
                                {{ message.message }}
                            </p>
                        </div>
                    </li>
                </ul>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @keydown="actionUser">
                <span v-if="isActive">{{isActive.name}} is typing....</span>
            </div>
            <div class="col-sm-4">
                <ul>
                    <li v-for="user in activeUsers">
                        <p>{{user.name}} <span class="text-success">(online)</span> |
                        <a v-bind:href="['/room/' + (roomId) + '/bane/' + (user.id)]" v-if="ownRoom">BANE</a>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['room', 'user', 'roommessages'],
        data() {
            return {
                messages: this.roommessages,
                textMessage: '',
                isActive: false,
                ownRoom: false,
                typingTimer: false,
                roomId: this.room.id,
                activeUsers: [],
            }
        },
        created() {
            this.checkBane();
        },
        computed: {
            channel() {
                return window.Echo.join('room.' + this.room.id);
            }
        },
        mounted() {
            this.channel
                .here((users) => {
                    this.activeUsers = users;
                })
                .joining((user) => {
                    this.activeUsers.push(user);
                })
                .leaving((user) => {
                    this.activeUsers = this.activeUsers.filter(u => {
                        return u.id != user.id
                    });
                })
                .listen('PrivateChat', ({data}) => {
                    console.log(data);
                    this.messages.push({
                        message: data.body,
                        user: data.user_data
                    });
                })
                .listenForWhisper('typing', (e) => {
                    this.isActive = e;
                    console.log(e);
                    if (this.typingTimer) clearTimeout(this.typingTimer);

                    this.typingTimer = setTimeout(() => {
                        this.isActive = false
                    }, 2000);
                })
        },
        methods: {
            sendMessage() {
                axios.post('/messages', {body: this.textMessage, room_id: this.room.id, user_id: this.user.id, user_data: this.user});

                this.messages.push({
                    message: this.textMessage,
                    user: this.user
                });
                this.textMessage = '';
            },
            actionUser() {
                this.channel
                    .whisper('typing', {
                        name: this.user.name
                    })
            },
            checkBane() {
                if (this.room.primary_user == this.user.id) {
                    this.ownRoom = true;
                }
            }
        }
    }
</script>
