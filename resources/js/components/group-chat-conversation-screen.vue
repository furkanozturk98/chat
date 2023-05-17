<template>
  <div v-if="groupConversation" class="col-md-9">
    <add-group-member-modal :selected-group="groupConversation.id" />
    <group-member-list-modal :selected-group="groupConversation.id" :current-member="currentMember" :members="groupMembers" />
    <div class="conversation-settings-tray" :class="{'settings-tray-dark': nightMode}">
      <div
        class="friend-drawer no-gutters"
        :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }"
        style="margin-bottom:-13px"
      >
        <img
          class="profile-image"
          :src="'images/'+groupConversation.image"
          alt=""
        >
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ groupConversation.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ groupConversation.about }}
          </p>
        </div>

        <span v-if="groupConversation" class="settings-tray--right" style="margin-left:220px">
          <a
            id="dropdownMenu2"
            role="button"
            data-toggle="dropdown"
            style=" cursor: pointer"
          >
            <i class="material-icons">menu</i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
              <button
                v-if="currentMember && currentMember.type !== 0"
                class="dropdown-item group-member-list"
                type="button"
                @click="showAddGroupMemberModal"
              >Add Group Member</button>
              <button
                class="dropdown-item group-member-list"
                type="button"
                @click="showGroupMemberListModal"
              >Group Member List</button>
            </div>
          </a>

          <b-dropdown
            ref="dropdown"
            right
            size="lg"
            variant="link"
            toggle-class="text-decoration-none"
            no-caret
          >
            <b-dropdown-item>
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              Add Group Member
            </b-dropdown-item>
            <b-dropdown-item>
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              Group Member List
            </b-dropdown-item>
          </b-dropdown>

        </span>
      </div>
    </div>

    <div class="chat-panel no-border" :class="{ 'chat-panel-dark': nightMode } ">
      <div class="overflow-auto" style="height:70vh">
        <edit-group-message-modal />

        <group-message-list
          :key="messageListKey"
          :items="items"
          :current-user="currentUser"
          :current-member="currentMember"
          :selected-group="groupConversation.id"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="">
          <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
            <emoji-picker :data="data" class="mb-1" @emoji:picked="handleEmojiPicked" />

            <file-upload
              ref="upload"
              :post-action="postActionUrl"
              :headers="{'Authorization': 'Bearer '+currentUser.api_token}"
              style="cursor:pointer;"
              accept="image/png,image/jpeg"
              @input-file="inputFile"
            >
              <b-icon icon="paperclip" scale="1.5" class="ml-2" style="color:#808080;" aria-hidden="true" />
            </file-upload>

            <input
              ref="input"
              v-model="form.message"
              type="text"
              placeholder="Type your message here..."
              class="message"
              @keyup.enter="sendMessage"
              @input="updateBody($event.target.value)"
              @click="handleEditorClick"
            >
            <a @click="sendMessage"><i class="material-icons">send</i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GroupMessageList from './group-message-list';
import EditGroupMessageModal from './modals/edit-group-message-modal';
import Form from 'form-backend-validation';
import AddGroupMemberModal from './modals/send-group-invite-modal';
import GroupMemberListModal from './modals/group-member-list-modal';
import data from '@zaichaopan/emoji-picker/data/emojis.json';

export default {
    name: 'GroupChatConversationScreen',
    components: {
        AddGroupMemberModal,
        GroupMessageList,
        EditGroupMessageModal,
        GroupMemberListModal,
    },

    props : {
        currentUser: {
            type: Object,
            default: null
        },
    },

    data() {
        return {
            items: [],
            groupConversation: null,
            groupMembers: null,
            nightMode: false,
            messageListKey: 0,
            currentMember: null,
            form: new Form({
                message: '',
                group_id: null,
                user_id: null
            }),

            data,
        }
    },

    computed : {
        postActionUrl() {
            return '/api/group-messages?group_id='+this.groupConversation?.id+'&user_id='+this.currentMember?.id
        }
    },

    mounted() {
        this.$eventHub.$on('groupClick', this.groupClicked);
        this.$eventHub.$on('friendClick', this.friendClicked);
        this.$eventHub.$on('nightModeOn', this.nightModeOn);
        this.nightMode = (localStorage.getItem('nightMode') === 'true')
    },

    methods: {

        inputFile(newFile, oldFile) {
            this.$refs.upload.active = true;

            if (newFile && oldFile && !newFile.active && oldFile.active) {
                // Get response data
                console.log('response', newFile.response.data)
            }
        },

        async fetch() {
            const response = await this.$http.get('/api/group-messages?group_id=' + this.groupConversation.id);
            this.items = response.data.data;
        },

        async getGroupMembers(){
            const response = await this.$http.get('/api/group-members?group_id=' + this.groupConversation.id);
            this.groupMembers = response.data.data;

            for(let i=0;i< this.groupMembers.length; i++)
            {
                if(this.groupMembers[i].member.id === this.currentUser.id){
                    this.currentMember = this.groupMembers[i];
                }
            }

        },

        async sendMessage() {
            if(this.form.message === null || this.form.message === ''){
                return;
            }

            this.form.group_id = this.groupConversation.id;
            this.form.user_id = this.currentMember.member.id;

            await this.form.post('/api/group-messages');

        },

        groupClicked(groupConversation) {
            this.groupConversation = groupConversation

            this.fetch();

            this.getGroupMembers();
        },

        friendClicked() {
            this.groupConversation = null;
        },

        nightModeOn() {
            if (this.nightMode === true) {
                this.nightMode = false;
                localStorage.setItem('nightMode', false);
            } else {
                this.nightMode = true;
                localStorage.setItem('nightMode', true);
            }
        },

        showAddGroupMemberModal(){
            this.$eventHub.$emit('showAddGroupMemberModal');
        },

        showGroupMemberListModal(){
            this.$eventHub.$emit('showGroupMemberListModal',this.groupConversation);
        },

        updateBody(text) {
            this.form.message = text;
        },

        handleEmojiPicked(emoji) {
            this.form.message = `${
                this.form.message
            } ${emoji}`;
            this.updateBody(this.form.message);
        },

        handleEditorClick() {
            this.focusEditor();
        },

        focusEditor() {
            this.$refs.input.focus();
        }
    }
}
</script>
