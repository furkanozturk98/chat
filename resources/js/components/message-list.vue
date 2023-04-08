<template>
  <div id="messageDisplay">
    <div v-for="item in items" :key="item.id" class="row no-gutters">
      <div
        class="col-md-5"
        :class="item.from === currentUser.id ? 'offset-md-9':''"
      >
        <div
          class="chat-bubble"
          :class="item.from === currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
        >
          <div class="row">
            <div class="col-9">
              <div v-if="item.deleted_at">
                <i>This message has been deleted</i>
              </div>
              <div v-else>
                {{ item.message }}

                <img v-if="item.image" :src="'chat/'+item.image" alt="" style="height:150px;width:auto;max-width:180px;padding: 5px">
              </div>
            </div>
            <div class="col-3">
              <span v-if="item.from === currentUser.id && !item.deleted_at && isBlocked === null" style="float:right">
                <a
                  id="dropdownMenu3"
                  role="button"
                  data-toggle="dropdown"
                  style=" cursor: pointer"
                >
                  <i class="bi bi-chevron-down" />
                  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                    <button
                      v-if="item.message"
                      class="dropdown-item edit-message"
                      type="button"
                      @click="showEditMessageModal(item)"
                    >Edit Message</button>

                    <button
                      class="dropdown-item delete-message"
                      type="button"
                      @click="deleteMessage(item.id)"
                    >Delete
                      Message</button>
                  </div>
                </a>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-9">
              <div style="font-size: 10px">
                {{ item.deleted_at === null && item.updated_at ? item.created_at + '(edited)' : item.created_at }}
              </div>
            </div>
            <div class="col-3">
              <div v-if="item.from === currentUser.id && item.deleted_at === null">
                <i v-if="item.status === 0" class="fas fa-check fa-x" />
                <i v-if="item.status === 1" class="fas fa-check-double fa-x" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isBlocked !== null">
      <div v-if="isBlocked === currentUser.id" class="row no-gutters" style="color: #fff;margin-left: 45%">
        <b-alert show>
          You have blocked this user.
        </b-alert>
      </div>
      <div v-else class="row no-gutters" style="color: #fff;margin-left: 45%">
        <b-alert show>
          You have been blocked by this user.
        </b-alert>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: 'MessageList',
    props: ['items', 'currentUser', 'selectedFriend', 'isBlocked'],

    data(){
      return{
          consoleOpened: false
        }
    },

    watch : {
        items: {
            handler (val, oldVal) {
                let self = this;
                setTimeout(()=> {
                    self.scrollToBottom();
                }, 1000);

            }
        }
    },

    mounted(){
        this.$eventHub.$on('messageEdited',this.messageEdited);

        this.$eventHub.$on('messageReceived',this.messageReceived);

        this.$eventHub.$on('messageDeleted',this.messageDeleted);

        this.$eventHub.$on('messageSeen', this.messageSeen);

    },

    methods: {

        scrollToBottom() {
            if(!window.devtools.isOpen){
                let element = document.getElementById('messageDisplay');
                element.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        },

        showEditMessageModal(item) {
            this.$eventHub.$emit('showEditMessageModal', item);
        },

        messageEdited(data){
            this.items.forEach(item => {
                if(item.id === data.id){
                   item.message = data.message;
                   item.updated_at = 'sss';
                }
            })
        },

        messageReceived(message){
            console.log(this.selectedFriend);
            if(this.selectedFriend && message.from === this.selectedFriend.id){
                this.items.push(message);
                this.$http.put('/api/messages/'+ message.id + '/receive');
            }
        },

        messageDeleted(id){
            //this.items = this.items.filter(item => item.id !== id);
            this.items.forEach(item => {
                if(item.id === id){
                    item.deleted_at = '1234';
                }
            })
        },

        messageSeen(data){
            this.items.forEach(item => {
                if(data.includes(item.id )){
                    item.status = 1;
                }
            })
        },

        async deleteMessage(id) {

            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete selected message.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.delete('api/messages/' + id)
                            .then(() => {
                                Vue.$toast.open({
                                    message: 'Message deleted successfully.',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });

                                this.messageDeleted(id);
                            })
                            .catch(() => {
                                Vue.$toast.open({
                                    message: 'An error occurred.',
                                    type: 'error',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            })
                    }

                });
        }
    }
}
</script>
