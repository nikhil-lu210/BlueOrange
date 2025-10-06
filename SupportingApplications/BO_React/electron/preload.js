const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('electronAPI', {
  
  send: (channel, data) => ipcRenderer.send(channel, data),
  on: (channel, cb) => ipcRenderer.on(channel, (event, ...args) => cb(...args)),
  ping: () => ipcRenderer.send('ping'),
  onMessage: (callback) => ipcRenderer.on('message', callback)
});
