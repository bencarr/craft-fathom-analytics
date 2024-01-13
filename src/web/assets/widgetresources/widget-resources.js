(function() {
    Craft.Fathom = {};
    Craft.Fathom.widget = function (widgetId) {
        const widget = window.dashboard.widgets[widgetId]
        widget.markAsLoading = () => widget.$container.addClass('loading')
        widget.unmarkAsLoading = () => widget.$container.removeClass('loading')

        return widget
    }

    Craft.Fathom.reload = function (widgetId) {
        const widget = Craft.Fathom.widget(widgetId)
        widget.markAsLoading()
        Craft.queue.push(() => new Promise((resolve) => {
            const data = {
                widgetId: widgetId,
            }
            Craft.sendActionRequest('POST', 'dashboard/save-widget-settings', { data })
                .then((response) => {
                    widget.update(response.data)
                })
                .catch(({ response }) => {
                    Craft.cp.displayError(Craft.t('widgetkit', 'Failed to refresh widget.'))
                })
                .finally(() => {
                    widget.$container.removeClass('loading')
                    resolve()
                })
        }))
    }
})()
