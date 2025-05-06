const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { ToggleControl } = wp.components;
const { useSelect, useDispatch } = wp.data;

registerPlugin('myplugin-hide-toggle', {
  render: () => {
    const meta = useSelect((s) => s('core/editor').getEditedPostAttribute('meta'));
    const { editPost } = useDispatch('core/editor');

    return (
      <PluginDocumentSettingPanel name="myplugin" title="My Plugin">
        <ToggleControl
          label="Hide plugin content"
          checked={!!meta._myplugin_hide_content}
          onChange={(v) => editPost({ meta: { ...meta, _myplugin_hide_content: v } })}
        />
      </PluginDocumentSettingPanel>
    );
  }
});
