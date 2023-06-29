<template>
  <div id="messageDisplay">
    <group-message-info-modal :current-user="currentUser" />
    <div v-for="item in items" :key="item.id" class="row">
      <div
        class="col-md-4"
        :class="item.from_id === currentUser.id ? 'offset-md-8':''"
      >
        <div
          class="chat-bubble"
          :class="item.from_id === currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
        >
          <div class="row">
            <div class="col-12">
              <b>{{ item.from_name }}</b>
            </div>
          </div>
          <div class="row">
            <div class="col-9">
              <div v-if="item.deleted_at">
                <i>This message has been deleted {{ (item.from_id !== item.deleted_by && item.deleted_by !== null )? 'by admin' : '' }}</i>
              </div>
              <div v-else>
                {{ item.message }}

                <img v-if="item.image" :src="'chat/'+item.image" alt="" style="height:150px;width:auto;max-width:180px;padding: 5px">
              </div>
            </div>
            <div class="col-3">
              <span v-if="(item.from_id === currentUser.id || (currentMember.type > item.from_type)) && !item.deleted_at" style="float:right;">
                <a
                  id="dropdownMenu3"
                  role="button"
                  data-toggle="dropdown"
                  style=" cursor: pointer"
                >
                  <i class="bi bi-chevron-down" />
                  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                    <button
                      v-if="item.message && item.from_id === currentUser.id"
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

                    <button
                      v-if="item.from_id === currentUser.id"
                      class="dropdown-item delete-message"
                      type="button"
                      @click="showMessageInfoModal(item.id)"
                    >Message Info
                    </button>
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
            <div class="col-3" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GroupMessageInfoModal from './modals/group-message-info-modal';
export default {
    name: 'GroupMessageList',

    components : {
        GroupMessageInfoModal
    },

    props: ['items', 'currentUser', 'selectedGroup', 'currentMember'],

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
        this.$eventHub.$on('group-message-edited', this.groupMessageEdited);

        this.$eventHub.$on('group-message-deleted', this.groupMessageDeleted);

        this.$eventHub.$on('group-message-received', this.groupMessageReceived);

    },

    methods: {
        scrollToBottom() {
            if(!window.devtools.isOpen) {
                let element = document.getElementById('messageDisplay');
                element.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        },

        showEditMessageModal(item) {
            this.$eventHub.$emit('showEditGroupMessageModal', item);
        },

        showMessageInfoModal(messageId){
            this.$eventHub.$emit('showGroupMessageInfoModal',this.selectedGroup,messageId);
        },

        groupMessageEdited(data) {
            this.items.forEach(item => {
                if (item.id === data.id) {
                    item.message = data.message;
                    item.updated_at = '123';
                }
            })
        },

        groupMessageReceived(message) {
            if (message.group_id === this.selectedGroup) {
                this.items.push(message);
                this.$http.put('/api/messages/'+ message.id + '/receive');
            }
        },

        groupMessageDeleted(id,deletedBy){
            this.items.forEach(item => {
                if(item.id === id){
                    item.deleted_at = '1234';
                    item.deleted_by = deletedBy;
                }
            })
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
                        this.$http.delete('api/group-messages/' + id)
                            .then((res) => {
                                Vue.$toast.open({
                                    message: 'Message deleted successfully.',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });

                                this.groupMessageDeleted(id,res.data.data);
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
