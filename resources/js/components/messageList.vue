<template>
  <div>
    <div v-for="item in items" :key="item.id" class="row no-gutters">
      <div
        class="col-md-3"
        :class="item.from === currentUser.id ? 'offset-md-9':''"
      >
        <div
          class="chat-bubble"
          :class="item.from === currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
        >
          {{ item.message }}

          <span v-if="item.from === currentUser.id" style="float:right">
            <a
              id="dropdownMenu3"
              role="button"
              data-toggle="dropdown"
              style=" cursor: pointer"
            >
              <i class="bi bi-chevron-down" />
              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                <button
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
    </div>
  </div>
</template>

<script>
export default {
    name: 'MessageList',
    props: ['items', 'currentUser', 'selectedFriend'],

    mounted(){
        this.$eventHub.$on('messageEdited',this.messageEdited);

        this.$eventHub.$on('messageReceived',this.messageReceived);
    },

    methods: {
        showEditMessageModal(item) {

            this.$eventHub.$emit('showEditMessageModal', item);
        },

        messageEdited(data){
            this.items.forEach(item => {
                if(item.id === data.id){
                   item.message = data.message;
                }
            })
        },

        messageReceived(message){
            if(this.selectedFriend && message.from === this.selectedFriend.id){
                this.items.push(message);
            }
        },

        messageDeleted(id){
            this.items = this.items.filter(item => item.id !== id);
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
                        this.$http.delete('api/message/delete/' + id)
                            .then(() => {
                                Vue.$toast.open({
                                    message: 'Message deleted successfully.',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });

                                this.messageDeleted(id);
                                //this.$eventHub.$emit('messageDeleted',id);
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

<style scoped>

</style>
