(function() {
    Craft.Fathom = {};
    Craft.Fathom.widget = function (widgetId) {
        const widget = window.dashboard.widgets[widgetId]
        widget.markAsLoading = () => widget.$container.addClass('loading')
        widget.unmarkAsLoading = () => widget.$container.removeClass('loading')

        return widget
    }

    Craft.Fathom.reload = function (widgetId) {
        Craft.Fathom.save(widgetId)
    }

    Craft.Fathom.patch = function (widgetId, updated = {}) {
        const widget = Craft.Fathom.widget(widgetId)
        const mergedSettings = Object.assign(widget.storedSettings, updated);

        return Craft.Fathom.save(widgetId, mergedSettings)
    }

    Craft.Fathom.save = function (widgetId, settings = {}) {
        const widget = Craft.Fathom.widget(widgetId)
        widget.markAsLoading()

        Craft.queue.push(() => new Promise((resolve) => {
            const data = {
                widgetId: widgetId,
                [`widget${widgetId}-settings`]: settings,
            }
            console.log('Craft.Fathom.save()', settings)
            Craft.sendActionRequest('POST', 'dashboard/save-widget-settings', { data })
                .then((response) => {
                    // widget.update(response.data)
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
