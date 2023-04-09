<template>
  <div v-if="conversation" class="col-md-9">
    <div class="conversation-settings-tray" :class="{'settings-tray-dark': nightMode}">
      <div
        class="friend-drawer no-gutters"
        :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }"
        style="margin-bottom:-13px"
      >
        <img
          class="profile-image"
          :src="'images/'+conversation.friend.image"
          alt=""
        >
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ conversation.friend.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ conversation.friend.about }}
          </p>
        </div>

        <span v-if="conversation" class="settings-tray--right" style="margin-left:220px">
          <a
            id="dropdownMenu3"
            role="button"
            data-toggle="dropdown"
            style=" cursor: pointer"
          >
            <i class="material-icons">menu</i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu3">
              <button
                :disabled="conversation.blocked_by !== null && conversation.blocked_by !== currentUser.id"
                class="dropdown-item edit-message"
                type="button"
                @click="conversation.blocked_by === null ? block(conversation.friend.id) : unblock(conversation.friend.id)"
              >{{ conversation.blocked_by === null ? 'Block': 'Unblock' }}</button>
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
            <b-dropdown-item @click="block(conversation.friend.id)">
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              {{ conversation.blocked_by !== null ? 'UnBlock': 'Block' }}
            </b-dropdown-item>
          </b-dropdown>
        </span>
      </div>
    </div>

    <div class="chat-panel no-border" :class="{ 'chat-panel-dark': nightMode } ">
      <div class="overflow-auto" style="height:70vh;">
        <edit-message-modal />

        <message-list
          :key="messageListKey"
          :items="items"
          :current-user="currentUser"
          :selected-friend="conversation.friend"
          :is-blocked="conversation.blocked_by"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
          <emoji-picker :data="data" class="mb-1" @emoji:picked="handleEmojiPicked" />

          <file-upload
            ref="upload"
            :post-action="'/api/messages?room_id='+conversation.roomId+'&user_id='+conversation.friend.id"
            :headers="{'Authorization': 'Bearer '+currentUser.api_token}"
            style="cursor:pointer;"
            :disabled="conversation.blocked_by !== null"
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
            :disabled="conversation.blocked_by !== null"
            @keyup.enter="sendMessage"
            @input="updateBody($event.target.value)"
            @click="handleEditorClick"
          >
          <a style="cursor:pointer;" :class="{'disabled': conversation.blocked_by !== null}" @click="sendMessage"><i class="material-icons">send</i></a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EditMessageModal from './modals/edit-message-modal';
import MessageList from './message-list';
import Form from 'form-backend-validation';
import data from '@zaichaopan/emoji-picker/data/emojis.json';

export default {
    name: 'ChatConversationScreen',
    components: { MessageList, EditMessageModal },
    props: ['currentUser',],

    data() {
        return {
            items: [],
            conversation: null,
            groupConversation: null,
            nightMode: false,
            messageListKey: 0,
            form: new Form({
                message: '',
            }),
            data,
            token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        }
    },

    mounted() {
        this.$eventHub.$on('friendClick', this.friendClicked);
        this.$eventHub.$on('groupClick', this.groupClicked);
        this.$eventHub.$on('nightModeOn', this.nightModeOn);
        this.$eventHub.$on('userBlocked', this.userBlocked);

        this.nightMode = (localStorage.getItem('nightMode') === 'true')
    },

    methods: {

        inputFile(newFile, oldFile) {
            this.$refs.upload.active = true;

            if (newFile && oldFile && !newFile.active && oldFile.active) {
                // Get response data
                console.log('response', newFile.response.data)

                this.items.push( newFile.response.data);
            }
        },

        async sendMessage() {
            if (this.form.message === null) {
                return;
            }
            const response = await this.form.post('/api/messages?room_id=' + this.conversation.roomId + '&user_id=' + this.conversation.friend.id);
            this.items.push(response.data);
        },

        async fetch() {
            const response = await this.$http.get('/api/messages?room_id=' + this.conversation.roomId);
            this.items = response.data.data;
        },

        friendClicked(conversation) {
            this.conversation = conversation

            this.fetch();
        },

        groupClicked() {
            this.conversation = null;
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

        forceRerender() {
            this.messageListKey += 1;
        },

        block(id) {
            this.$http.put('api/friends/'+ id + '/block')
                .then(res => {
                    this.conversation.blocked_by = res.data.data.blocked_by
                });
        },

        unblock(id) {
            this.$http.put('api/friends/'+ id + 'unblock')
                .then(res => {
                    this.conversation.blocked_by = res.data.data.blocked_by
                });
        },

        userBlocked(data){
            this.conversation.blocked_by = data.data.blocked_by;
        },

        updateBody(text) {
            this.form.message = text;
        },
        handleEmojiPicked(emoji) {
            if(this.conversation.blocked_by !== null){
                return;
            }

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

<style>
.disabled {
    pointer-events:none;
    opacity:0.6;
}
</style>
