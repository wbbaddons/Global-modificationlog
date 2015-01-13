###
# @author	Joshua Rüsweg
# @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
# @package	be.bastelstu.josh.globalmodificationlog
###

(($, window) ->
	"use strict";
	
	console =
		log: (message) ->
			window.console.log "[be.bastelstu.josh.globalmodificationlog] #{message}" unless production?
		warn: (message) ->
			window.console.warn "[be.bastelstu.josh.globalmodificationlog] #{message}" unless production?
		error: (message) ->
			window.console.error "[be.bastelstu.josh.globalmodificationlog] #{message}" unless production?
 
	Handler = Class.extend
		actionOption: null
		objectTypeButton: null
		optionSupport: [ ]
		options: [ ]
		
		init: (actionOption, objectTypeButton) ->
			@actionOption = $ actionOption
			@objectTypeButton = $ objectTypeButton
			
			console.log("unable to find actionOption") unless @actionOption.length
			console.log("unable to find objectTypeButton") unless @objectTypeButton.length
			
			@objectTypeButton.on 'change', => @changeObjectType do @objectTypeButton.val
			
			# first init the actionInput
			#@changeObjectType @objectTypeButton.val
			
		addOption: (action, support) ->
			@optionSupport[action] = [ ]
			@options[action] = $ """<option value="#{action}">#{WCF.Language.get 'wcf.acp.modification.log.action.'+action}</option>"""
			@actionOption.append @options[action]
			
			# add support for objectTypes
			@addSupport action, support
			
		selectAction: (action) ->
			@options[action].prop('selected', true)
			
		addSupport: (action, support) -> 
			for objectType of support
				@optionSupport[action][objectType] = support[objectType]
			
		disableAllOptions: ->
			for action of @options
				@options[action].prop('disabled', true)
			
		enableAllOptions: ->
			for action of @options
				@options[action].prop('disabled', false)
			
		changeObjectType: (objectType) ->
			do @enableAllOptions if objectType is "all"
			
			unless objectType is "all"
				do @disableAllOptions

				for action of @optionSupport
					for objectTypeOption of @optionSupport[action]
						@options[action].prop('disabled', false) if objectTypeOption is objectType and @optionSupport[action][objectTypeOption]
						
				# check whether the current value is disabled
				unless @actionOption.val()?
					for action of @optionSupport
						for objectTypeOption of @optionSupport[action]
							if objectTypeOption is objectType and @optionSupport[action][objectTypeOption]
								return @selectAction(action)
			

	window.be ?= {}
	be.bastelstu ?= {}
	be.bastelstu.josh ?= {}
	be.bastelstu.josh.globalmodificationlog ?= {}
	be.bastelstu.josh.globalmodificationlog.Handler = Handler
	
)(jQuery, @)