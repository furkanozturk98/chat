<template>
  <b-modal
    ref="group-message-info"
    title="Group Message Information"
    size="sm"
    hide-footer
  >
    <div v-for="item in items" :key="item.id">
      <div class="friend-drawer ">
        <img
          class="profile-image"
          :src="'images/'+item.member.image"
          alt=""
        >
        <div class="text">
          <h6>
            <b>{{ item.member.name }}</b>
          </h6>
            <p class="text-muted">
                {{ item.updated_at }}
            </p>
        </div>
        <span class="ml-5">
          <i v-if="item.status === 0" class="fas fa-check fa-x" />
          <i v-if="item.status === 1" class="fas fa-check-double fa-x" />
        </span>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
name: 'GroupMessageInfoModal',
    props: ['currentUser'],
    data() {
        return {
            items: null,
            groupId: null,
            messageId: null
        }
    },

    mounted() {
        this.$eventHub.$on('showGroupMessageInfoModal', this.showGroupMessageInfoModal);

        this.$eventHub.$on('groupMessageSeen', this.groupMessageSeen);

    },

    methods: {
        async fetch(){
            const response = await this.$http.get('/api/group-message-info/group/' + this.groupId + '/message/'+ this.messageId);
            this.items = response.data.data;
        },

        showGroupMessageInfoModal(groupId,messageId) {
            this.messageId = messageId;
            this.groupId = groupId;

            this.fetch();

            this.$refs['group-message-info'].show();
        },

        groupMessageSeen(data){
            this.items.forEach(item => {
                console.log(data.messageId === item.message.id && data.groupId === item.group && data.receiverId === item.member.id)
                if(data.messageId === item.message.id && data.groupId === item.group && data.receiverId === item.member.id){
                    item.status = 1;
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
