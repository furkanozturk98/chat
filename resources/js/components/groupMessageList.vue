<template>
  <div id="messageDisplay">
    <div v-for="item in items" :key="item.id" class="row no-gutters">
      <div
        class="col-md-3"
        :class="item.sender !== currentUser.id ? 'offset-md-9':''"
      >
        <div
          class="chat-bubble"
          :class="item.sender !== currentUser.id ? 'chat-bubble--right': 'chat-bubble--left'"
        >
          {{ item.message }}

          <span v-if="item.sender !== currentUser.id" style="float:right">
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
name: 'GroupMessageList',
    props: ['items', 'currentUser', 'selectedGroup'],

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
        this.$eventHub.$on('messageEdited',this.groupMessageEdited);

        this.$eventHub.$on('messageReceived',this.groupMessageReceived);
    },

    methods: {
        scrollToBottom() {
            let element = document.getElementById('messageDisplay');
            element.scrollIntoView({ behavior: 'smooth', block: 'end' });
        },

        showEditMessageModal(item) {
            this.$eventHub.$emit('showEditMessageModal', item);
        },

        groupMessageEdited(data) {
            this.items.forEach(item => {
                if (item.id === data.id) {
                    item.message = data.message;
                }
            })
        },

        groupMessageReceived(message) {
            if (this.selectedFriend && message.from === this.selectedFriend.id) {
                this.items.push(message);
            }
        },

        messageDeleted(id) {
            this.items = this.items.filter(item => item.id !== id);
        },

        async deleteMessage(id) {

        }
    }
}
</script>
