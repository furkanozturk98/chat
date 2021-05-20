<template>
  <div id="messageDisplay">
    <div v-for="item in items" :key="item.id" class="row no-gutters">
      <div
        class="col-md-3"
        :class="item.sender.id === currentUser.id ? 'offset-md-9':''"
      >
        <div
          class="chat-bubble"
          :class="item.sender.id === currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
        >
          <div class="row">
            <div class="col-9">
              <div v-show="item.sender.id !== currentUser.id" class="chat-buble-name">
                <b>{{ item.sender.name }}</b>
              </div>
              {{ item.message }}
              <br>
              <div style="font-size: 10px">
                {{ item.created_at }}
              </div>
            </div>
            <div v-if="item.sender.id === currentUser.id" style="float:right;margin-top:12%" class="col-3">
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
                  >Edit Message
                  </button>

                  <button
                    class="dropdown-item delete-message"
                    type="button"
                    @click="deleteMessage(item.id)"
                  >Delete
                    Message
                  </button>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: 'GroupMessageList',
    props: ['items', 'currentUser', 'selectedGroup'],

    watch: {
        items: {
            handler(val, oldVal) {
                let self = this;
                setTimeout(() => {
                    self.scrollToBottom();
                }, 1000);

            }
        }
    },

    mounted() {
        this.$eventHub.$on('groupMessageEdited', this.groupMessageEdited);

        this.$eventHub.$on('groupMessageReceived', this.groupMessageReceived);
    },

    methods: {
        scrollToBottom() {
            let element = document.getElementById('messageDisplay');
            element.scrollIntoView({ behavior: 'smooth', block: 'end' });
        },

        showEditMessageModal(item) {
            this.$eventHub.$emit('showEditGroupMessageModal', item);
        },

        groupMessageEdited(data) {
            this.items.forEach(item => {
                if (item.id === data.id) {
                    item.message = data.message;
                }
            })
        },

        groupMessageReceived(message) {
            if (message.group.id === this.selectedGroup) {
                this.items.push(message);
            }
        },

        messageDeleted(id) {
            this.items = this.items.filter(item => item.id !== id);
        },

        deleteMessage(id) {
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
                        this.$http.delete('api/group-message/' + id)
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
